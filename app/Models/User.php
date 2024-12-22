<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;

use Filament\Panel;
use App\Models\Support\Address;
use Illuminate\Support\Collection;
use App\Enums\Parentship\ParentType;
use App\Enums\UserRole;
use App\Models\Admission\Registrant;
use App\Observers\UserObserver;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Filament\Models\Contracts\HasTenants;
use Filament\Models\Contracts\FilamentUser;
use Illuminate\Database\Eloquent\Attributes\ObservedBy;
use Illuminate\Database\Eloquent\Casts\AsEnumCollection;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphOne;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

#[ObservedBy(UserObserver::class)]
class User extends Authenticatable implements FilamentUser, HasTenants
{
    use HasFactory, Notifiable, SoftDeletes;

    protected $fillable = [
        'name',
        'nik',
        'phone',
        'email',
        'password',
        'roles'
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'roles' => AsEnumCollection::of(UserRole::class)
        ];
    }

    public function canAccessPanel(Panel $panel): bool
    {
        return match ($panel->getId()) {
            'admission' => $this->registrants()->count() > 0,
            'admin' => count($this->roles ?? []) > 0,
            default => false,
        };
    }

    public function getTenants(Panel $panel): array | Collection
    {
        return $this->childrens;
    }

    public function canAccessTenant(Model $tenant): bool
    {
        return $this->childrens()->whereKey($tenant)->exists();
    }

    public function addresses(): MorphMany
    {
        return $this->morphMany(Address::class, 'addressable');
    }

    public function address(): MorphOne
    {
        return $this->addresses()->one()->latestOfMany();
    }

    public function profile(): HasOne
    {
        return $this->hasOne(UserProfile::class, 'user_id');
    }

    public function parents(): HasMany
    {
        return $this->hasMany(UserParent::class);
    }

    public function childrens(): BelongsToMany
    {
        return $this->belongsToMany(User::class, UserParent::class, 'parent_id', 'user_id')
            ->withPivot('type')
            ->withTimestamps();
    }

    public function father(): HasOne
    {
        return $this->parents()->whereType(ParentType::Father)->one();
    }

    public function mother(): HasOne
    {
        return $this->parents()->whereType(ParentType::Mother)->one();
    }

    public function trustee(): HasOne
    {
        return $this->parents()->whereType(ParentType::Trustee)->one();
    }

    public function other(): HasOne
    {
        return $this->parents()->whereType(ParentType::Other)->one()->latestOfMany();
    }

    public function employees(): HasMany
    {
        return $this->hasMany(Employee::class);
    }

    public function employee(): HasOne
    {
        return $this->employees()->one()->latestOfMany();
    }

    public function registrants(): HasMany
    {
        return $this->hasMany(Registrant::class);
    }

    public function files(): HasMany
    {
        return $this->hasMany(UserFile::class);
    }
}
