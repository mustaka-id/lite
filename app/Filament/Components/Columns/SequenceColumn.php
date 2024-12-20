<?php

namespace App\Filament\Components\Columns;

use Filament\Tables\Columns\TextColumn;

class SequenceColumn extends TextColumn
{
    public static function make(string $name = 'sequence'): static
    {
        return parent::make($name)
            ->width(1)
            ->label('')
            ->alignRight()
            ->prefix('#');
    }
}
