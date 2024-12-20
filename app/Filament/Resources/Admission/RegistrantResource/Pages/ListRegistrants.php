<?php

namespace App\Filament\Resources\Admission\RegistrantResource\Pages;

use App\Filament\Resources\Admission\RegistrantResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListRegistrants extends ListRecords
{
    protected static string $resource = RegistrantResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
