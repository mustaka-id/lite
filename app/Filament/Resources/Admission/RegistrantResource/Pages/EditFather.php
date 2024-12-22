<?php

namespace App\Filament\Resources\Admission\RegistrantResource\Pages;

use App\Enums\Parentship\ParentType;
use Filament\Forms;
use Filament\Actions;
use Filament\Forms\Form;
use App\Enums\User\ProfileSex;
use App\Enums\User\ProfileReligion;
use App\Enums\Tenancy\EducationDegree;
use Illuminate\Database\Eloquent\Model;
use Filament\Resources\Pages\EditRecord;
use App\Filament\Resources\Admission\RegistrantResource;
use App\Filament\Resources\UserResource\Components\UserForm;
use App\Filament\Resources\UserResource\Components\UserProfileForm;
use App\Models\User;
use App\Models\UserParent;

class EditFather extends EditRecord
{
    protected static string $resource = RegistrantResource::class;

    protected static ?string $title = 'Father';

    protected static ?string $navigationIcon = 'heroicon-o-user';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Group::make([
                    Forms\Components\Group::make([
                        Forms\Components\Select::make('type')
                            ->options(ParentType::class)
                            ->default(ParentType::Father)
                            ->disabled()
                            ->dehydrated(),
                        Forms\Components\Group::make([
                            Forms\Components\Section::make([
                                UserForm::getNameField(),
                                UserForm::getNikField(),
                                UserForm::getPhoneField(),
                                UserForm::getEmailField()
                            ])->columns(2),
                            Forms\Components\Section::make('Profile')
                                ->relationship('profile')
                                ->schema([
                                    UserProfileForm::getSexField(),
                                    UserProfileForm::getBloodTypeField(),
                                    UserProfileForm::getKKNumberField(),
                                    UserProfileForm::getPobField(),
                                    UserProfileForm::getDobField(),
                                    UserProfileForm::getReligionField(),
                                    UserProfileForm::getNationalityField(),
                                    UserProfileForm::getIsAliveField(),
                                ])->columns(2)
                        ])->relationship('user'),
                    ])->relationship('father')
                ])->relationship('user')->columnSpanFull()
            ]);
    }
}
