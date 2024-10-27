<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Sample;

class SampleController extends Controller
{
    public function updateOrCreate(Request $request)
    {
        // Validate incoming request
        $request->validate([
            'path' => 'nullable|file',
            'path_2' => 'nullable|file',
        ]);

        // Retrieve or create the first row in the Sample table
        $sample = Sample::firstOrNew();

        // Handle file upload for 'file'
        if ($request->hasFile('path')) {
            $filePath = $request->file('path')->store('samples', 'public');
            $sample->path = $filePath;
        }

        // Handle file upload for 'file'
        if ($request->hasFile('path_2')) {
            $filePath = $request->file('path_2')->store('samples', 'public');
            $sample->path_2 = $filePath;
        }

        // Save the Sample instance
        $sample->save();

        // Return success message
        return redirect()->back()->with('success', 'Sample updated successfully!');
    }
}
