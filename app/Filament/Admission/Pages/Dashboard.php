<?php

namespace App\Filament\Admission\Pages;

use App\Filament\Admission\Pages\User;
use App\Filament\Admission\Resources\UserEducationResource;
use App\Filament\Admission\Resources\UserFileResource;
use App\Models\Admission\Registrant;
use App\Models\Support\Announcement;
use App\Models\User as UserModel;
use Carbon\Carbon;
use Filament\Notifications\Notification;
use Filament\Pages\Dashboard as Page;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class Dashboard extends Page
{
    protected static string $view = 'filament.admission.pages.dashboard';

    public ?UserModel $user = null;
    public ?Registrant $registrant = null;

    public Collection | array $announcements;

    public ?string $appointment_at = null;

    public function mount(): void
    {
        $this->user = Auth::user();
        $this->registrant = $this->user->registrants()?->with('bills.items', 'payments')->first();
        $this->announcements = Announcement::active()->get();

        $this->appointment_at = $this->registrant->meta['appointment_at'] ?? null;
    }

    public function saveAppointment(): void
    {
        $meta = $this->registrant->meta;
        $meta['appointment_at'] = $this->appointment_at;

        $this->registrant->update(['meta' => $meta]);

        Notification::make()
            ->success()
            ->title(__('Interview schedule has been arranged'))
            ->send();
    }

    public function getSteps(): Collection
    {
        return collect([
            [
                "label" => 'Terdaftar',
                "value" => $this->registrant->registered_at,
            ],
            [
                "label" => 'Terverifikasi',
                "value" => $this->registrant->verified_at,
            ],
            [
                "label" => 'Data valid',
                "value" => $this->registrant->validated_at,
            ],
            [
                "label" => 'Lunas Pembayaran',
                "value" => $this->registrant->paid_off_at,
            ],
            [
                "label" => 'Diterima',
                "value" => $this->registrant->accepted_at,
            ],
        ]);
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
                "label" => 'Jadwal Wawancara',
                'url' => null,
                "value" => isset($this->registrant->meta['appointment_at']),
                'description' => $this->registrant->meta['appointment_at'] ? Carbon::parse($this->registrant->meta['appointment_at'])->isoFormat('LLLL') : null
            ],
            [
                "label" => 'Alamat Pendaftar',
                'url' => Address::getUrl(),
                "value" => $this->user->address,
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
