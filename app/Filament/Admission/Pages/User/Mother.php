<?php

namespace App\Filament\Admission\Pages\User;

use App\Enums\Parentship\ParentType;
use Illuminate\Contracts\Support\Htmlable;

class Mother extends Father
{
    protected static ?int $navigationSort = 3;

    protected static ?string $navigationIcon = 'heroicon-o-users';

    public static function getNavigationLabel(): string
    {
        return __('Mother Data');
    }

    public function getTitle(): string | Htmlable
    {
        return self::getNavigationLabel();
    }

    public function getParentType(): ParentType
    {
        return ParentType::Mother;
    }
}
