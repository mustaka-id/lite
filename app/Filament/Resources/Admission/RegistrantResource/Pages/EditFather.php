<?php

namespace App\Filament\Resources\Admission\RegistrantResource\Pages;

use App\Enums\Parentship\ParentType;
use Filament\Forms;
use Filament\Forms\Form;
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

    public function beforeSave(): void
    {
        // Dapatkan model user terkait
        $user = $this->record->user;

        // Periksa apakah data father sudah ada
        if (!$user->father) {
            // Ambil data father dari form
            $fatherData = $this->data['user']['father']['user'];

            // Buat user baru untuk father
            $fatherUser = User::create($fatherData);

            // Simpan hubungan antara user dan father
            $father = new UserParent();
            $father->user_id = $user->id; // Gunakan ID user yang ada
            $father->parent_id = $fatherUser->id; // Gunakan ID user father yang baru dibuat
            $father->type = ParentType::Father;
            $father->save();
        }
    }
}
