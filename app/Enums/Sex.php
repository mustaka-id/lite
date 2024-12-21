<?php

namespace App\Enums;

use Filament\Support\Contracts\HasLabel;

enum Sex: int implements HasLabel
{
    case Male = 1;
    case Female = 2;

    public function getLabel(): ?string
    {
        return __($this->name);
    }
}
