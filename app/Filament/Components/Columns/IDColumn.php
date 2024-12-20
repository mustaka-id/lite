<?php

namespace App\Filament\Components\Columns;

use Filament\Tables\Columns\TextColumn;

class IDColumn extends TextColumn
{
    public static function make(string $name = 'id'): static
    {
        return parent::make($name)
            ->label(strtoupper($name))
            ->sortable()
            ->toggleable(isToggledHiddenByDefault: true);
    }
}
