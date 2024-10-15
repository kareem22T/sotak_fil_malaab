<?php

namespace App\Http\Controllers;

use App\Models\Application;
use Illuminate\Http\Request;

class DownloadController extends Controller
{
    public function downloadVideo(Application $application)
    {
        // Logic to download the video goes here
        // For example:
        $video = $application->video; // Assuming you have a video relationship on the Application model
        return response()->download($video);
    }
}
