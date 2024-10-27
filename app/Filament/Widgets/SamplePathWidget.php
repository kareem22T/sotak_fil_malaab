<?php

namespace App\Filament\Widgets;

use Filament\Widgets\Widget;
use App\Models\Sample;
use Filament\Forms;
use Filament\Forms\Components\FileUpload;
use Illuminate\Support\Facades\DB;

class SamplePathWidget extends Widget implements Forms\Contracts\HasForms
{
    use Forms\Concerns\InteractsWithForms;

    protected static string $view = 'filament.widgets.sample-path-widget';

    public $path = "";
    public $path_2 = "";

    protected function getFormSchema(): array
    {
        return [
            FileUpload::make('path')
                ->label('Video 1')
                ->required(), // Keep original file names (optional)

            FileUpload::make('path_2') // Update key to 'path_2' to match the property
                ->label('Video 2'),
        ];
    }

    public function mount(): void
    {
        // Load existing data for the first row if it exists
        $sample = Sample::first();
        if ($sample) {
            $this->path = $sample->path;
            $this->path_2 = $sample->path_2;
        }
    }

    public function savePaths(): void
    {
        $validatedData = $this->validate([
            'path' => 'required|file', // Add validation rules
            'path_2' => 'nullable|file', // 'nullable' if path_2 isn't mandatory
        ]);

        $sample = Sample::firstOrNew(['id' => 1]); // Assumes updating the first row

        // Ensure paths are stored correctly after validation
        $sample->path = $this->path;
        $sample->path_2 = $this->path_2;
        $sample->save();
    }

    protected function getFormModel(): Sample
    {
        return new Sample();
    }
}
