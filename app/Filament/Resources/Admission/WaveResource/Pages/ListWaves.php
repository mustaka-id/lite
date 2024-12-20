<?php

namespace App\Filament\Resources\Admission\WaveResource\Pages;

use App\Filament\Resources\Admission\WaveResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListWaves extends ListRecords
{
    protected static string $resource = WaveResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
