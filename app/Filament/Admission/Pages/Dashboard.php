<?php

namespace App\Filament\Admission\Pages;

use App\Filament\Admission\Pages\User;
use App\Filament\Admission\Resources\UserEducationResource;
use App\Filament\Admission\Resources\UserFileResource;
use App\Models\Admission\Registrant;
use App\Models\User as UserModel;
use Filament\Pages\Dashboard as Page;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class Dashboard extends Page
{
    protected static string $view = 'filament.admission.pages.dashboard';

    public ?UserModel $user = null;
    public ?Registrant $registrant = null;

    public function mount(): void
    {
        $this->user = Auth::user();
        $this->registrant = $this->user->registrants()?->with('bills.items', 'payments')->first();
    }

    public function getCompleteness(): Collection
    {
        return collect([
            [
                "label" => 'Profil Pendaftar',
                'url' => Profile::getUrl(),
                "value" => $this->user->phone,
            ],
            [
                "label" => 'Data Ayah',
                'url' => User\Father::getUrl(),
                "value" => isset($this->user->father->parent),
            ],
            [
                "label" => 'Data Ibu',
                'url' => User\Mother::getUrl(),
                "value" => isset($this->user->mother->parent),
            ],
            [
                "label" => 'Riwayat Pendidikan',
                'url' => UserEducationResource::getUrl('index'),
                "value" => $this->user->educations?->count() > 0,
            ],
            [
                "label" => 'Berkas Pendaftaran',
                'url' => UserFileResource::getUrl('index'),
                "value" => $this->registrant->files->filter(fn($file) => $file->required && !Storage::exists($file->path ?? '-1'))->count() == 0
            ]
        ]);
    }
}
