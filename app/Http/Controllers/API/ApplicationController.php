<?php
namespace App\Http\Controllers\API;

use App\Models\Application;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;

class ApplicationController extends Controller
{
    public function postApplication(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string',
            'dob' => 'required|date',
            'gender' => 'required|string',
            'phone' => 'required|string',
            'email' => 'required|email',
            'governoment' => 'required|string',
            'educational_qualification' => 'required|string',
            'languages' => 'required',
            'accept_terms' => 'required|boolean',
            'video' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors(), 'notes' => ['Invalid data']], 400);
        }

        $user = $request->user();

        $application = Application::where('user_id', $user->id)->first();

        if (!$application)
            $application = Application::create([
                'user_id' => $user->id,
                'name' => $request->name,
                'dob' => $request->dob,
                'gender' => $request->gender,
                'phone' => $request->phone,
                'email' => $request->email,
                'governoment' => $request->governoment,
                'educational_qualification' => $request->educational_qualification,
                'languages' => json_encode($request->languages),
                'accept_terms' => $request->accept_terms,
                'video' => $request->file('video')->store('applications', 'public'),
            ]);
        else
            return response()->json(['errors' => ["application" => "application already exists"], 'notes' => ['You have already registerd']], 400);


        return response()->json(['application' => $application, 'notes' => ['Application posted successfully']], 201);
    }

    public function rateApplication(Request $request, Application $application)
    {
        $validator = Validator::make($request->all(), [
            'rate' => 'required|integer|min:1|max:5'
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors(), 'notes' => ['Invalid data']], 400);
        }

        // Check if the user has already rated this application
        $existingRate = $application->rates()->where('user_id', $request->user()->id)->first();

        if ($existingRate) {
            return response()->json(['notes' => ['You have already rated this application']], 400);
        }

        $application->rates()->create([
            'user_id' => $request->user()->id,
            'rate' => $request->rate,
        ]);

        return response()->json(['notes' => ['Application rated successfully']], 200);
    }

    public function getApplications(Request $request)
    {
        $query = Application::query();

        if ($request->has('sort')) {
            if ($request->sort === 'most_rated') {
                $query->withCount('rates')->orderBy('rates_count', 'desc');
            } elseif ($request->sort === 'latest') {
                $query->orderBy('created_at', 'desc');
            }
        }

        $applications = $query->paginate(20);

        return response()->json(['applications' => $applications, 'notes' => ['Applications fetched successfully']], 200);
    }
    public function getApplication($id)
    {
        // Check if the application exists, along with its relationships (rates and user)
        $application = Application::with(['rates.user', 'user'])->find($id);

        // If the application doesn't exist, return a 404 response
        if (!$application) {
            return response()->json(['notes' => ['Application not found']], 404);
        }

        return response()->json([
            'application' => [
                'id' => $application->id,
                'name' => $application->name,
                'dob' => $application->dob,
                'gender' => $application->gender,
                'phone' => $application->phone,
                'email' => $application->email,
                'governoment' => $application->governoment,
                'educational_qualification' => $application->educational_qualification,
                'languages' => $application->languages,
                'accept_terms' => $application->accept_terms,
                'video' => $application->video,  // This will now return the full URL
                'admin_rate' => $application->admin_rate,
                'user' => $application->user, // Include user information
                'rates' => $application->rates, // Include rates and the users who rated
                'created_at' => $application->created_at,
                'updated_at' => $application->updated_at,
            ],
            'notes' => ['Application fetched successfully']
        ], 200);
    }
    }
