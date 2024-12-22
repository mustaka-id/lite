<?php

namespace App\Filament\Resources\Admission\RegistrantResource\Pages;

use App\Enums\Parentship\ParentType;
use Filament\Forms;
use Filament\Forms\Form;
use Illuminate\Database\Eloquent\Model;
use Filament\Resources\Pages\EditRecord;
use App\Filament\Resources\Admission\RegistrantResource;
use App\Filament\Resources\Support\AddressResource\Components\AddressableForm;
use App\Filament\Resources\UserResource\Components\UserForm;
use App\Filament\Resources\UserResource\Components\UserProfileForm;
use App\Models\User;
use App\Models\UserParent;

class EditTrustee extends EditRecord
{
    protected static string $resource = RegistrantResource::class;

    protected static ?string $title = 'Trustee';

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
                                ])->columns(2),
                            AddressableForm::make()
                        ])->relationship('user'),
                    ])->relationship('trustee')
                ])->relationship('user')->columnSpanFull()
            ]);
    }

    public function beforeSave(): void
    {
        // Dapatkan model user terkait
        $user = $this->record->user;

        // Periksa apakah data trustee sudah ada
        if (!$user->trustee) {
            // Ambil data trustee dari form
            $trusteeData = $this->data['user']['trustee']['user'];

            // Buat user baru untuk trustee
            $trusteeUser = User::create($trusteeData);

            // Simpan hubungan antara user dan trustee
            $trustee = new UserParent();
            $trustee->user_id = $user->id; // Gunakan ID user yang ada
            $trustee->parent_id = $trusteeUser->id; // Gunakan ID user trustee yang baru dibuat
            $trustee->type = ParentType::Trustee;
            $trustee->save();
        }
    }
}
