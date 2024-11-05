<?php

namespace App\Filament\Resources\ApplicationResource\Widgets;

use App\Models\Application;
use Filament\Widgets\Widget;

class ImageWidget extends Widget
{
    public ?Application $record = null; // Ensure we can pass user ID when using the widget

    protected static bool $isLazy = false;
    protected static string $view = 'filament.resources.application-resource.widgets.image-widget';
    protected int|string|array $columnSpan = 'full';
}
