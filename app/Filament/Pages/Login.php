<?php

namespace App\Filament\Pages;

use Filament\Facades\Filament;
use Filament\Pages\Auth\Login as Page;

class Login extends Page
{
    public function mount(): void
    {
        if (Filament::auth()->check()) {
            redirect()->intended(Filament::getUrl());
        }

        if (!app()->isProduction()) {
            $this->form->fill([
                'email' => env('APP_TEST_EMAIL', 'test@example.com'),
                'password' => 'password'
            ]);
        }
    }
}
