<?php

namespace App\Settings;

use Spatie\LaravelSettings\Settings;

class SchoolSetting extends Settings
{
    public static function group(): string
    {
        return 'school';
    }
}
