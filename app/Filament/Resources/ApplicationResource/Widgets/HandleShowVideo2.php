<?php

namespace App\Filament\Resources\ApplicationResource\Widgets;

use App\Models\Application;
use App\Models\Sample;
use Filament\Widgets\Widget;

class HandleShowVideo2 extends Widget
{
    protected static string $view = 'filament.resources.application-resource.widgets.handle-show-video2';
    public ?Application $record = null; // Ensure we can pass user ID when using the widget
    protected static bool $isLazy = false;
    protected int|string|array $rowSpan = 'full';

    public ?string $video2;
    public ?string $sample2;

    public function mount()
    {
        $sample2 = Sample::select('title', 'sub_title', 'description', 'video')->find(2);

        $this->video2 = asset('storage/' . $this->record->video_2);
        $this->sample2 = asset('storage/' . $sample2->video);
    }

}
