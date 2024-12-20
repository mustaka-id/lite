<?php

namespace App\Filament\Resources\Admission\RegistrantResource\Pages;

use App\Filament\Resources\Admission\RegistrantResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditRegistrant extends EditRecord
{
    protected static string $resource = RegistrantResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
            Actions\ForceDeleteAction::make(),
            Actions\RestoreAction::make(),
        ];
    }
}
