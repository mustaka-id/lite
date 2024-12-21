<?php

namespace App\Filament\Resources\Admission\RegistrantResource\Pages;

use Filament\Forms;
use Filament\Actions;
use Filament\Forms\Form;
use App\Enums\User\ProfileSex;
use App\Enums\User\ProfileReligion;
use App\Enums\Tenancy\EducationDegree;
use Illuminate\Database\Eloquent\Model;
use Filament\Resources\Pages\EditRecord;
use App\Filament\Resources\Admission\RegistrantResource;
use App\Filament\Resources\UserResource\Components\UserProfileForm;

class EditProfile extends EditRecord
{
    protected static string $resource = RegistrantResource::class;

    protected static ?string $title = 'Profile';

    protected static ?string $navigationIcon = 'heroicon-o-document';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Group::make([
                    Forms\Components\Section::make('Profile')
                        ->relationship('profile')
                        ->schema([
                            UserProfileForm::getSexField(),
                            UserProfileForm::getNisnField(),
                            UserProfileForm::getKKNumberField(),
                            UserProfileForm::getPobField(),
                            UserProfileForm::getDobField(),
                            UserProfileForm::getReligionField(),
                            UserProfileForm::getSiblingsCountField(),
                            UserProfileForm::getChildOrderField(),
                            UserProfileForm::getAspirationField(),
                            UserProfileForm::getLastEducationField(),
                            UserProfileForm::getNationalityField(),
                            UserProfileForm::getIsAliveField(),
                        ])->columns(2)
                ])->columnSpanFull()
            ]);
    }
}
