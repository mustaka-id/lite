<?php

namespace App\Filament\Admission\Pages\User;

use App\Enums\Parentship\ParentType;

class Mother extends Father
{
    protected static ?int $navigationSort = 3;

    protected static ?string $navigationIcon = 'heroicon-o-users';

    public function getParentType(): ParentType
    {
        return ParentType::Mother;
    }
}
