<?php

namespace App\Enums\Support;

use Filament\Support\Contracts\HasLabel;

enum GradeLevel: int implements HasLabel
{
    case I = 1;
    case II = 2;
    case III = 3;
    case IV = 4;
    case V = 5;
    case VI = 6;
    case VII = 7;
    case VIII = 8;
    case IX = 9;
    case X = 10;
    case XI = 11;
    case XII = 12;

    public function getLabel(): ?string
    {
        return $this->name;
    }
}
