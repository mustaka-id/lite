<?php

namespace App\Enums\Support;

use Filament\Support\Contracts\HasLabel;

enum Grade: string implements HasLabel
{
    case TK = 'tk';
    case SD = 'sd';
    case SMP = 'smp';
    case SMA = 'sma';
    case D3 = 'd3';
    case S1 = 's1';
    case S2 = 's2';
    case S3 = 's3';

    public function getLabel(): ?string
    {
        return match ($this) {
            self::TK => 'TK/RA/Sederajat',
            self::SD => 'SD/MI/Sederajat',
            self::SMP => 'SMP/MTs/Sederajat',
            self::SMA => 'SMA/MA/Sederajat',
            self::D3 => 'Diploma 3',
            self::S1 => 'Strata 1',
            self::S2 => 'Strata 2',
            self::S3 => 'Strata 3',
        };
    }
}
