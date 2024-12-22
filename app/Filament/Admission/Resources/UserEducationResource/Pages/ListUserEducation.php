<?php

namespace App\Filament\Admission\Resources\UserEducationResource\Pages;

use App\Filament\Admission\Resources\UserEducationResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use Filament\Support\Enums\MaxWidth;

class ListUserEducation extends ListRecords
{
    protected static string $resource = UserEducationResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()
                ->modalWidth(MaxWidth::Large),
        ];
    }
}
