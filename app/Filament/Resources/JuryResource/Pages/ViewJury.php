<?php

namespace App\Filament\Resources\JuryResource\Pages;

use App\Filament\Resources\JuryResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewJury extends ViewRecord
{
    protected static string $resource = JuryResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make(),
        ];
    }
}
