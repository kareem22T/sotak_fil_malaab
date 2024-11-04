<?php

namespace App\Filament\Resources\ApplicationResource\Pages;

use App\Filament\Resources\ApplicationResource;
use Filament\Actions;
use Filament\Actions\Action;
use Filament\Resources\Pages\EditRecord;

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

}
