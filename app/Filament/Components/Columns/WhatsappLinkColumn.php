<?php

namespace App\Filament\Components\Columns;

use Filament\Support\Enums\FontWeight;
use Filament\Support\Enums\IconPosition;
use Filament\Tables\Columns\TextColumn;

class WhatsappLinkColumn extends TextColumn
{
    public static function make(string $name = 'phone'): static
    {
        return parent::make($name)
            ->icon('heroicon-o-chat-bubble-oval-left-ellipsis')
            ->iconColor('success')
            ->url(fn(?string $state) => $state ? "https://wa.me/" . filter_var($state, FILTER_SANITIZE_NUMBER_INT) : null)
            ->openUrlInNewTab(fn(?string $state): bool => filled($state));
    }
}
