<?php

namespace App\Filament\Resources\Admission\WaveResource\Pages;

use App\Filament\Resources\Admission\WaveResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditWave extends EditRecord
{
    protected static string $resource = WaveResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
            Actions\ForceDeleteAction::make(),
            Actions\RestoreAction::make(),
        ];
    }
}
