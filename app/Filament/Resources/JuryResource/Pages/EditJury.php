<?php

namespace App\Filament\Resources\JuryResource\Pages;

use App\Filament\Resources\JuryResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditJury extends EditRecord
{
    protected static string $resource = JuryResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\ViewAction::make(),
            Actions\DeleteAction::make(),
        ];
    }
}
