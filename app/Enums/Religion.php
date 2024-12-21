<?php

namespace App\Enums;

use Filament\Support\Contracts\HasLabel;

enum Religion: string implements HasLabel
{
    case Islam = 'islam';
    case Chirst = 'chirst';
    case Catholic = 'catholic';
    case Hindu = 'hindu';
    case Buddha = 'buddha';

    public function getLabel(): ?string
    {
        return __($this->name);
    }
}
