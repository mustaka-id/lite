<?php

namespace App\Settings;

use Spatie\LaravelSettings\Settings;

class AdmissionSetting extends Settings
{
    public static function group(): string
    {
        return 'school';
    }
}
