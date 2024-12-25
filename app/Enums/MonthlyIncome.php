<?php

namespace App\Enums;

use Filament\Support\Contracts\HasLabel;

enum MonthlyIncome: string implements HasLabel
{
    case Tier1 = '< 500.000';
    case Tier2 = '500.001 s.d 1 juta';
    case Tier3 = '1 juta s.d. 2 juta';
    case Tier4 = '2 juta s.d. 3 juta';
    case Tier5 = '3 juta s.d. 5 juta';
    case Tier6 = 'diatas 5 juta';
    case Tier0 = 'tidak berpenghasilan';

    public function getLabel(): ?string
    {
        return __($this->value);
    }
}
