<?php

namespace App\Http\Controllers\API;

use App\Models\Application;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Sample;
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
            'video_2' => 'required',
        ]);

        if ($validator->fails()) {
            $firstError = $validator->errors()->first();
            return response()->json(['status' => false, 'msg' => $firstError, 'notes' => ['Invalid data']], 400);
        }

        $user = $request->user();
        $application = Application::where('user_id', $user->id)->first();

        if (!$application) {
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
                'video_2' => $request->file('video_2')->store('applications', 'public'),
            ]);
        } else {
            return response()->json(['status' => false, 'msg' => 'Application already exists', 'notes' => ['Application already exists']], 400);
        }

        return response()->json(['status' => true, 'msg' => 'Application posted successfully', 'data' => ['application' => $application], 'notes' => ['Application posted successfully']], 201);
    }

    public function getSamples() {
        $sample = Sample::first();
        $data = [
            "video_1" => $sample?->path,
            "video_2" => $sample?->path_2,
        ];
        return response()->json(['status' => true, 'msg' => 'Application posted successfully', 'data' => $data, 'notes' => ['samples got']], 201);
    }

    public function rateApplication(Request $request, Application $application)
    {
        $validator = Validator::make($request->all(), [
            'rate' => 'required|integer|min:1|max:5'
        ]);

        if ($validator->fails()) {
            $firstError = $validator->errors()->first();
            return response()->json(['status' => false, 'msg' => $firstError, 'notes' => ['Invalid data']], 400);
        }

        $existingRate = $application->rates()->where('user_id', $request->user()->id)->first();

        if ($existingRate) {
            return response()->json(['status' => false, 'msg' => 'You have already rated this application', 'notes' => ['You have already rated this application']], 400);
        }

        $application->rates()->create([
            'user_id' => $request->user()->id,
            'rate' => $request->rate,
        ]);

        return response()->json(['status' => true, 'msg' => 'Application rated successfully', 'data' => null, 'notes' => ['Application rated successfully']], 200);
    }

    public function getApplications(Request $request)
    {
        $query = Application::query();

        $query->with('user');

        if ($request->has('sort')) {
            if ($request->sort === 'most_rated') {
                $query->withCount('rates')->orderBy('rates_count', 'desc');
            } elseif ($request->sort === 'latest') {
                $query->orderBy('created_at', 'desc');
            }
        }

        $applications = $query->paginate(20);
        $applicationsWithAvgRate = $applications->getCollection()->map(function ($application) {
            return [
                'id' => $application->id,
                'name' => $application->name,
                'video1' => $application->video,
                'video2' => $application->video_2,
                'image' => !empty($application->user->photo) ? asset('storage/' . $application->user->photo) : null,
                'rate' => $application->rates->avg('rate'),
            ];
        });

        $paginatedApplications = $applications->setCollection($applicationsWithAvgRate);

        return response()->json(['status' => true, 'msg' => 'Applications fetched successfully', 'data' => ['applications' => $paginatedApplications], 'notes' => ['Applications fetched successfully']], 200);
    }

    public function getApplication($id)
    {
        $application = Application::with(['rates.user', 'user'])->find($id);

        if (!$application) {
            return response()->json(['status' => false, 'msg' => 'Application not found', 'notes' => ['Application not found']], 404);
        }

        return response()->json([
            'status' => true,
            'msg' => 'Application fetched successfully',
            'data' => [
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
                    'video' => $application->video,
                    'video_2' => $application->video_2,
                    'admin_rate' => $application->admin_rate,
                    'user' => $application->user,
                    'rates' => $application->rates,
                    'avg_rate' => $application->rates->avg('rate'),
                    'created_at' => $application->created_at,
                    'updated_at' => $application->updated_at,
                ]
            ],
            'notes' => ['Application fetched successfully']
        ], 200);
    }
}
