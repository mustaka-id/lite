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

class EditMother extends EditRecord
{
    protected static string $resource = RegistrantResource::class;

    protected static ?string $title = 'Mother';

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
                    ])->relationship('mother')
                ])->relationship('user')->columnSpanFull()
            ]);
    }

    public function beforeSave(): void
    {
        // Dapatkan model user terkait
        $user = $this->record->user;

        // Periksa apakah data Mother sudah ada
        if (!$user->Mother) {
            // Ambil data Mother dari form
            $motherData = $this->data['user']['mother']['user'];

            // Buat user baru untuk Mother
            $motherUser = User::create($motherData);

            // Simpan hubungan antara user dan Mother
            $mother = new UserParent();
            $mother->user_id = $user->id; // Gunakan ID user yang ada
            $mother->parent_id = $motherUser->id; // Gunakan ID user Mother yang baru dibuat
            $mother->type = ParentType::Mother;
            $mother->save();
        }
    }
}
