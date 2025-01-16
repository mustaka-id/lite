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
use App\Filament\Resources\Support\AddressResource\Components\AddressableForm;
use App\Filament\Resources\UserResource\Components\UserProfileForm;
use Filament\Forms\Components\Hidden;

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
                            Hidden::make('user_id')
                                ->default(fn() => $this->record->user->id),
                            UserProfileForm::getSexField(),
                            UserProfileForm::getBloodTypeField(),
                            UserProfileForm::getPobField(),
                            UserProfileForm::getDobField(),
                            UserProfileForm::getNisnField(),
                            UserProfileForm::getKKNumberField(),
                            UserProfileForm::getReligionField(),
                            UserProfileForm::getSiblingsCountField(),
                            UserProfileForm::getChildOrderField(),
                            UserProfileForm::getAspirationField(),
                            UserProfileForm::getNationalityField(),
                            UserProfileForm::getIsAliveField(),
                        ])->columns(2),
                    Forms\Components\Group::make([
                        Forms\Components\Section::make(__('Registrant photo'))
                            ->schema([
                                Forms\Components\FileUpload::make('avatar')
                                    ->label(__('Choose file'))
                                    ->image()
                                    ->directory('avatars')
                                    ->maxSize(1024)
                                    ->imageCropAspectRatio('3:4')
                                    ->imageEditor()
                                    ->helperText(fn() => view('components.user.avatar-instructions')),
                            ]),
                        AddressableForm::make('Address')
                    ])->relationship('user')
                ])->columnSpanFull()
            ]);
    }
}
