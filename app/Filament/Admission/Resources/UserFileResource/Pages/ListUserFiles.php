<?php

namespace App\Filament\Admission\Resources\UserFileResource\Pages;

use App\Filament\Admission\Resources\UserFileResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListUserFiles extends ListRecords
{
    protected static string $resource = UserFileResource::class;
}
