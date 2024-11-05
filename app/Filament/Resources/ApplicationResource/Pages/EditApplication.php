<?php

namespace App\Filament\Resources\ApplicationResource\Pages;

use App\Filament\Resources\ApplicationResource;
use App\Filament\Resources\ApplicationResource\Widgets\ImageWidget;
use Filament\Actions;
use Filament\Actions\Action;
use Filament\Resources\Pages\EditRecord;

use Filament\Infolists\Components\Image;
use Filament\Resources\Pages\ViewRecord;

class EditApplication extends EditRecord
{
    protected static string $resource = ApplicationResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
            Action::make('download_video')
            ->extraAttributes([
                'target' => '_blank',
                'download' => 'download',
                'random_shit' => 'this_works',
            ])

            ->label('Download Video 1')

            ->url(function ($record) {

                return ($record->video_1);

            })
            ->icon('heroicon-s-arrow-down-tray'),
            Action::make('download_video_2')
            ->extraAttributes([
                'target' => '_blank',
                'download' => 'download',
                'random_shit' => 'this_works',
            ])

            ->label('Download Video 2')

            ->url(function ($record) {

                return ($record->video_2);

            })
            ->icon('heroicon-s-arrow-down-tray'),

        ];
    }

    protected function getHeaderWidgets(): array
    {
        return [
            ImageWidget::make([
                'record' => $this->record,
            ]),
        ];
    }
}
