<?php

namespace App\Filament\Resources\ApplicationResource\Widgets;

use App\Models\Application;
use App\Models\Sample;
use Filament\Widgets\Widget;

class HandleShowVideo1 extends Widget
{
    protected static string $view = 'filament.resources.application-resource.widgets.handle-show-video1';

    public ?Application $record = null; // Ensure we can pass user ID when using the widget
    protected static bool $isLazy = false;

    public ?string $video1;
    public ?string $sample1;
    protected int|string|array $rowSpan = 'full';

    public function mount()
    {
        $sample1 = Sample::select('title', 'sub_title', 'description', 'video')->find(1);

        $this->video1 = asset('storage/' . $this->record->video_1);
        $this->sample1 = asset('storage/' . $sample1->video);
    }
}
