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

            ->label('Download Video')

            ->url(function ($record) {

                return ($record->video);

            })
            ->icon('heroicon-s-arrow-down-tray'),

        ];
    }

}
