<?php

namespace App\Enums\Support;

use Filament\Support\Contracts\HasLabel;

enum AnnouncementPlacement: string implements HasLabel
{
    case Homepage = 'homepage';
    case RegistrantDashboard = 'registrant_dashboard';

    public function getLabel(): ?string
    {
        return match ($this) {
            self::Homepage => __('Homepage'),
            self::RegistrantDashboard => __('Registrant Dashboard'),
        };
    }
}
