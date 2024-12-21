<?php

namespace App\Filament\Admission\Pages;

use App\Models\Admission\RegistrantBillItem;
use App\Models\Admission\Wave;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Pages\Auth\EditProfile as Page;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;

class Profile extends Page
{
    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make([
                    $this->getNameFormComponent(),
                    Forms\Components\Placeholder::make('created_at')
                        ->label('Joined at')
                        ->content(Auth::user()->created_at?->isoFormat('LLLL')),
                ]),
                Forms\Components\Section::make([
                    $this->getEmailFormComponent(),
                ]),
                Forms\Components\Section::make([
                    $this->getPasswordFormComponent(),
                    $this->getPasswordConfirmationFormComponent(),
                ])
            ]);
    }
}
