<?php

namespace App\Http\Controllers\API;

use App\Models\Application;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Sample;
use App\Models\Setting;
use Illuminate\Support\Facades\Validator;

class ApplicationController extends Controller
{
    public function postApplication(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'dob' => 'required|string',
            'gender' => 'required|string',
            'governoment' => 'required|string',
            'educational_qualification' => 'required|string',
            'languages' => 'required',
            'accept_terms' => 'required|boolean',
            'photo' => 'nullable|image',
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
                'name' => $user->name,
                'dob' => $request->dob,
                'gender' => $request->gender,
                'phone' => $user->phone,
                'email' => $user->email,
                'governoment' => $request->governoment,
                'educational_qualification' => $request->educational_qualification,
                'languages' => json_encode($request->languages),
                'accept_terms' => $request->accept_terms,
            ]);

            if (!$user->name)
                $user->update([
                    'name' => $request->name,
                ]);

            if ($request->hasFile('photo')) {
                $user->update([
                    'photo' => $request->file('photo') ? $request->file('photo')->store('photos', 'public') : null,
                ]);
            }
        } else {
            return response()->json(['status' => false, 'msg' => 'Application already exists', 'notes' => ['Application already exists']], 400);
        }

        $user->is_data_completed = true;
        $user->save();

        return response()->json(['status' => true, 'msg' => 'Application posted successfully', 'data' => ['application' => $application], 'notes' => ['Application posted successfully']], 201);
    }

    public function postApplicationVideos(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'video_1' => 'nullable',
            'video_2' => 'nullable',
        ]);

        if ($validator->fails()) {
            $firstError = $validator->errors()->first();
            return response()->json(['status' => false, 'msg' => $firstError, 'notes' => ['Invalid data']], 400);
        }

        $user = $request->user();
        $application = Application::where('user_id', $user->id)->first();

        if ($application) {
            if ($request->video_1)
                $application->update([
                    'video_1' => $request->file('video_1')->store('applications', 'public'),
                ]);
            if ($request->video_2)
                $application->update([
                    'video_2' => $request->file('video_2')->store('applications', 'public'),
                ]);
        } else {
            return response()->json(['status' => false, 'msg' => 'Data not completed', 'notes' => ['Data not completed']], 400);
        }


        $user->is_videos_uploaded = true;
        $user->save();

        $application->video_1 = $application->video_1 ? asset('storage/' . $application->video_1) : $application->video_1;
        $application->video_2 = $application->video_2 ? asset('storage/' . $application->video_2) : $application->video_2;

        return response()->json(['status' => true, 'msg' => 'video posted successfully', 'data' => ['application' => $application], 'notes' => ['Application completed successfully']], 201);
    }

    public function checkIsApplicationExists(Request $request)
    {
        $user = $request->user();

        $application = Application::where('user_id', $user->id)->first();

        if ($application) {
            if ($application->video_1 && $application->video_2)
                return response()->json(['status' => true, 'msg' => 'Application exists', 'data' => "BOTH_UPLOADED"], 200);
            if ($application->video_1)
                return response()->json(['status' => false, 'msg' => 'Application exists', 'data' => "VIDEO_1_UPLOADED"], 200);
            if ($application->video_2)
                return response()->json(['status' => false, 'msg' => 'Application exists', 'data' => "VIDEO_2_UPLOADED"], 200);

            return response()->json(['status' => false, 'msg' => 'videos not exists', 'data' => "No_VIDEOS"], 200);
        }
        return response()->json(['status' => false, 'msg' => 'Application not exists', 'data' => ""], 200);
    }

    public function getSamples(Request $request)
    {
        $sample1 = Sample::select('title', 'sub_title', 'description', 'video', 'thumbnail')->find(1);
        $sample2 = Sample::select('title', 'sub_title', 'description', 'video', 'thumbnail')->find(2);

        $sample1->video = asset('storage/' . $sample1->video);
        $sample2->video = asset('storage/' . $sample2->video);

        $application = Application::with(['rates.user', 'user'])->where('user_id', $request->user()->id)->first();

        $sample1->thumbnail = asset('storage/' . $sample1->thumbnail);
        $sample2->thumbnail = asset('storage/' . $sample2->thumbnail);

        $data = [
            "sample_1" => ($application?->video_1 && $application) ? null : $sample1,
            "sample_2" => ($application?->video_2  && $application) ?  null : $sample2
        ];

        return response()->json(['status' => true, 'msg' => 'Samples fetched successfully', 'data' => $data, 'notes' => ['samples got']], 201);
    }

    public function rateApplication(Request $request, Application $application)
    {
        $validator = Validator::make($request->all(), [
            'video' => 'required|string|in:video_1,video_2', // Accept only "video_1" or "video_2"
            'rate' => 'required|integer|min:1|max:5'
        ]);

        if ($validator->fails()) {
            $firstError = $validator->errors()->first();
            return response()->json(['status' => false, 'msg' => $firstError, 'notes' => ['Invalid data']], 400);
        }

        $video = $request->input('video');
        $userId = $request->user()->id;

        // Check if the user has already rated the selected video
        $existingRate = $application->rates()->where('user_id', $userId)->where('video', $video)->first();

        if ($existingRate) {
            return response()->json(['status' => false, 'msg' => 'You have already rated this video', 'notes' => ['Already rated']], 400);
        }

        // Save the rate for the specific video
        $application->rates()->create([
            'user_id' => $userId,
            'rate' => $request->rate,
            'video' => $video,
        ]);

        return response()->json(['status' => true, 'msg' => 'Video rated successfully', 'data' => null, 'notes' => ['Video rated successfully']], 200);
    }

    public function getApplications(Request $request)
    {
        $userId = $request->user()->id; // Get the current user's ID

        $sample1 = Sample::select('title', 'sub_title', 'description', 'video', 'thumbnail')->find(1);
        $sample2 = Sample::select('title', 'sub_title', 'description', 'video', 'thumbnail')->find(2);

        $sample1->video = asset('storage/' . $sample1->video);
        $sample2->video = asset('storage/' . $sample2->video);

        $sample1->thumbnail = asset('storage/' . $sample1->thumbnail);
        $sample2->thumbnail = asset('storage/' . $sample2->thumbnail);

        $query = Application::query();

        $query->where('video_1', '!=', null);
        $query->where('video_2', '!=', null);
        $query->where('is_approved', true);

        $query->with(['user', 'rates' => function ($query) use ($userId) {
            $query->where('user_id', $userId);
        }]);

        if ($request->has('sort')) {
            if ($request->sort === 'most_rated') {
                $query->select('applications.*')
                    ->selectSub(function ($query) {
                        $query->from('rates')
                            ->whereColumn('applications.id', 'rates.application_id')
                            ->selectRaw('SUM(rate)');
                    }, 'rates_sum_rate')
                    ->orderBy('rates_sum_rate', 'desc');
            } elseif ($request->sort === 'lowest_rated') {
                $query->select('applications.*')
                    ->selectSub(function ($query) {
                        $query->from('rates')
                            ->whereColumn('applications.id', 'rates.application_id')
                            ->selectRaw('MIN(rate)');
                    }, 'lowest_rate')
                    ->orderBy('lowest_rate', 'asc');
            } elseif ($request->sort === 'latest') {
                $query->orderBy('created_at', 'desc');
            }
        }

        $applications = $query->paginate(3);
        $applicationsWithAvgRate = $applications->getCollection()->map(function ($application) use ($sample2, $sample1, $userId) {
            $video1Rated = $application->rates->where('video', 'video_1')->isNotEmpty();
            $video2Rated = $application->rates->where('video', 'video_2')->isNotEmpty();

            return [
                'id' => $application->id,
                'name' => $application->name,
                'video_1' => $application->video_1 ? asset('storage/' . $application->video_1) : $application->video_1,
                'video_2' => $application->video_2 ? asset('storage/' . $application->video_2) : $application->video_2,
                'image' => !empty($application->user->photo) ? asset('storage/' . $application->user->photo) : null,
                'rate' => $application->rates->sum('rate'),
                'rate_video_1' => $application->ratesForVideo('video_1')->sum('rate') ?? 0,
                'rate_video_2' => $application->ratesForVideo('video_2')->sum('rate') ?? 0,
                'is_rated_video_1' => $video1Rated,
                'is_rated_video_2' => $video2Rated,
                'created_at' => $application->created_at,
                'sample_1' => $sample1,
                'sample_2' => $sample2,
            ];
        });

        $paginatedApplications = $applications->setCollection($applicationsWithAvgRate);
        $applications = []; // Initialize an empty array

        foreach ($paginatedApplications as $application) {
            $applications[] = [
                'id' => "video1_" . $application['id'],
                'rate' => $application['rate_video_1'],
                'total_rate' => (int) $application['rate_video_1'] + (int) $application['rate_video_2'],
                'is_ad' => false,
                'is_rated' => $application['is_rated_video_1'],
                'sample' => $application['sample_1']['video'],
                'video' => $application['video_1'],
                'image' => $application['image'],
                'name' => $application['name'] ?? null,
                'ad_content' => null,
            ];
            $applications[] = [
                'id' => "video2_" . $application['id'],
                'rate' => $application['rate_video_2'],
                'total_rate' => (int) $application['rate_video_1'] + (int) $application['rate_video_2'],
                'is_ad' => false,
                'is_rated' => $application['is_rated_video_2'],
                'sample' => $application['sample_2']['video'],
                'video' => $application['video_2'],
                'image' => $application['image'],
                'name' => $application['name'] ?? null,
                'ad_content' => null,
            ];
        }

        // Fetch setting for repeated ad
        $setting = Setting::select('interval_of_repeat', 'repeated_ad')->first();
        $interval = $setting->interval_of_repeat ?? 5;
        $repeatedAd = asset('storage/' . $setting->repeated_ad);

        // Insert repeated ads
        $result = [];
        foreach ($applications as $index => $application) {
            $result[] = $application;
            if (($index + 1) % $interval === 0) {
                $result[] = [
                    'id' => null,
                    'rate' => null,
                    'total_rate' => null,
                    'is_ad' => true,
                    'is_rated' => null,
                    'sample' => null,
                    'video' => null,
                    'image' => null,
                    'name' => null,
                    'ad_content' => $repeatedAd,
                ];
            }
        }

        return response()->json(['status' => true, 'msg' => 'Applications fetched successfully', 'data' => ['applications' => $result], 'notes' => ['Applications fetched successfully']], 200);
    }
    public function getApplicationsFUllNotAsReels(Request $request)
    {
        $userId = $request->user()->id; // Get the current user's ID

        $query = Application::query();

        $query->where('video_1', '!=', null);
        $query->where('video_2', '!=', null);
        $query->where('is_approved', true);

        $query->with(['user', 'rates' => function ($query) use ($userId) {
            $query->where('user_id', $userId);
        }]);

        if ($request->has('sort')) {
            if ($request->sort === 'most_rated') {
                $query->select('applications.*')
                    ->selectSub(function ($query) {
                        $query->from('rates')
                            ->whereColumn('applications.id', 'rates.application_id')
                            ->selectRaw('SUM(rate)');
                    }, 'rates_sum_rate')
                    ->orderBy('rates_sum_rate', 'desc');
            } elseif ($request->sort === 'lowest_rated') {
                $query->select('applications.*')
                    ->selectSub(function ($query) {
                        $query->from('rates')
                            ->whereColumn('applications.id', 'rates.application_id')
                            ->selectRaw('MIN(rate)');
                    }, 'lowest_rate')
                    ->orderBy('lowest_rate', 'asc');
            } elseif ($request->sort === 'latest') {
                $query->orderBy('created_at', 'desc');
            }
        }

        $applications = $query->paginate(10);
        $applicationsWithAvgRate = $applications->getCollection()->map(function ($application) {
            $video1Rated = $application->rates->where('video', 'video_1')->isNotEmpty();
            $video2Rated = $application->rates->where('video', 'video_2')->isNotEmpty();

            return [
                'id' => $application->id,
                'name' => $application->name,
                'image' => !empty($application->user->photo) ? asset('storage/' . $application->user->photo) : null,
                'rate' => (int) ($application->ratesForVideo('video_1')->sum('rate') ?? 0) + (int) ($application->ratesForVideo('video_2')->sum('rate') ?? 0),
            ];
        });

        $paginatedApplications = $applications->setCollection($applicationsWithAvgRate);

        return response()->json(['status' => true, 'msg' => 'Applications fetched successfully', 'data' => ['applications' => $paginatedApplications], 'notes' => ['Applications fetched successfully']], 200);
    }
        public function getVideoById($id)
    {
        // Split the ID to determine the video type and application ID
        [$videoType, $applicationId] = explode('_', $id);

        if (!in_array($videoType, ['video1', 'video2'])) {
            return response()->json(['status' => false, 'msg' => 'Invalid video type'], 400);
        }

        // Find the application by ID
        $application = Application::with('user')->find($applicationId);

        if (!$application) {
            return response()->json(['status' => false, 'msg' => 'Application not found'], 404);
        }

        // Determine which video to return
        $videoKey = $videoType === 'video1' ? 'video_1' : 'video_2';
        $video = $application->$videoKey ? asset('storage/' . $application->$videoKey) : null;

        if (!$video) {
            return response()->json(['status' => false, 'msg' => 'Video not found'], 404);
        }

        $sample1 = Sample::select('title', 'sub_title', 'description', 'video', 'thumbnail')->find(1);
        $sample2 = Sample::select('title', 'sub_title', 'description', 'video', 'thumbnail')->find(2);

        $sample1->video = asset('storage/' . $sample1->video);
        $sample2->video = asset('storage/' . $sample2->video);

        $sample1->thumbnail = asset('storage/' . $sample1->thumbnail);
        $sample2->thumbnail = asset('storage/' . $sample2->thumbnail);

        // Prepare response data
        $response = [
            'id' => $id,
            'name' => $application->name,
            'video' => $video,
            'sample' => $videoKey == 'video_1' ? $sample1->video : $sample2->video,
            'image' => $application->user->photo ? asset('storage/' . $application->user->photo) : null,
            'rate' => $application->ratesForVideo($videoKey)->sum('rate') ?? 0,
            'created_at' => $application->created_at,
        ];

        return response()->json(['status' => true, 'msg' => 'Video fetched successfully', 'data' => $response], 200);
    }

    public function getApplication($id)
    {
        $sample1 = Sample::select('title', 'sub_title', 'description', 'video', 'thumbnail')->find(1);
        $sample2 = Sample::select('title', 'sub_title', 'description', 'video', 'thumbnail')->find(2);

        $sample1->video = asset('storage/' . $sample1->video);
        $sample2->video = asset('storage/' . $sample2->video);

        $sample1->thumbnail = asset('storage/' . $sample1->thumbnail);
        $sample2->thumbnail = asset('storage/' . $sample2->thumbnail);

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
                    'video_1' => $application->video_1 ? asset('storage/' . $application->video_1) : $application->video_1,
                    'video_2' => $application->video_2 ? asset('storage/' . $application->video_2) : $application->video_2,
                    'rate_video_1' => $application->ratesForVideo('video_1')->sum('rate') ?? 0,
                    'rate_video_2' => $application->ratesForVideo('video_2')->sum('rate') ?? 0,
                    'created_at' => $application->created_at,
                    'sample_1' => $sample1,
                    'sample_2' => $sample2,
                ]
            ],
            'notes' => ['Application fetched successfully']
        ], 200);
    }

    public function getUserApplication(Request $request)
    {
        $sample1 = Sample::select('title', 'sub_title', 'description', 'video', 'thumbnail')->find(1);
        $sample2 = Sample::select('title', 'sub_title', 'description', 'video', 'thumbnail')->find(2);

        $sample1->video = asset('storage/' . $sample1->video);
        $sample2->video = asset('storage/' . $sample2->video);

        $sample1->thumbnail = asset('storage/' . $sample1->thumbnail);
        $sample2->thumbnail = asset('storage/' . $sample2->thumbnail);

        $application = Application::with(['rates.user', 'user'])->where('user_id', $request->user()->id)->first();

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
                    'video_1' => $application->video_1 ? asset('storage/' . $application->video_1) : $application->video_1,
                    'video_2' => $application->video_2 ? asset('storage/' . $application->video_2) : $application->video_2,
                    'admin_rate' => $application->admin_rate,
                    'is_approved' => $application->is_approved,
                    'user' => $application->user,
                    'rates' => $application->rates,
                    'rate_video_1' => $application->ratesForVideo('video_1')->sum('rate') ?? 0,
                    'rate_video_2' => $application->ratesForVideo('video_2')->sum('rate') ?? 0,
                    'created_at' => $application->created_at,
                    'updated_at' => $application->updated_at,
                    'sample_1' => $sample1,
                    'sample_2' => $sample2,
                ]
            ],
            'notes' => ['Application fetched successfully']
        ], 200);
    }
}
