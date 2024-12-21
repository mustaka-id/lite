<?php

namespace App\Enums\Parentship;

use Filament\Support\Contracts\HasLabel;

enum ParentType: string implements HasLabel
{
    case Father = 'father';
    case Mother = 'mother';
    case Trustee = 'trustee';
    case Other = 'other';

    public function getLabel(): ?string
    {
        return __($this->name);
    }
}
