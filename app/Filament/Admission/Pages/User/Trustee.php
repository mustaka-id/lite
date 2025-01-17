<?php

namespace App\Filament\Admission\Pages\User;

use App\Enums\Parentship\ParentType;
use Illuminate\Contracts\Support\Htmlable;

class Trustee extends Father
{
    protected static ?int $navigationSort = 4;

    protected static ?string $navigationIcon = 'heroicon-o-users';

    public static function getNavigationLabel(): string
    {
        return __('Trustee Data');
    }

    public function getTitle(): string | Htmlable
    {
        return self::getNavigationLabel();
    }

    public function getParentType(): ParentType
    {
        return ParentType::Trustee;
    }
}
