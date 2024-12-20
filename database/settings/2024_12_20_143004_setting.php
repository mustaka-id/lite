<?php

use Spatie\LaravelSettings\Migrations\SettingsMigration;

return new class extends SettingsMigration
{
    public function up(): void
    {
        $this->migrator->add('school.name', null);
        $this->migrator->add('school.phone', null);
        $this->migrator->add('school.email', null);
        $this->migrator->add('school.address', null);
        $this->migrator->add('school.socials', null);
        $this->migrator->add('school.npsn', null);
    }
};
