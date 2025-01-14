<?php

namespace App\Filament\Admission\Resources\UserEducationResource\Pages;

use App\Filament\Admission\Resources\UserEducationResource;
use App\Models\Admission\Registrant;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use Filament\Support\Enums\MaxWidth;
use Illuminate\Support\Facades\Auth;

class ListUserEducation extends ListRecords
{
    protected static string $resource = UserEducationResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()
                ->modalWidth(MaxWidth::Large)
                ->disabled(isset(Registrant::latest()->whereBelongsTo(Auth::user())->first()?->registered_at)),
        ];
    }
}
