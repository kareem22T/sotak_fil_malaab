<?php

namespace App\Filament\Resources\JuryResource\Pages;

use App\Filament\Resources\JuryResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListJuries extends ListRecords
{
    protected static string $resource = JuryResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
