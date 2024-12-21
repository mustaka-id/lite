<?php

namespace App\Models\Admission;

use App\Models\Support\Note;
use App\Models\User;
use App\Models\UserFile;
use App\Models\UserProfile;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\Relations\MorphOne;
use Illuminate\Database\Eloquent\SoftDeletes;

class Registrant extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'adm_registrant';

    protected $fillable = [
        'wave_id',
        'user_id',
        'registered_by',
        'registered_at',
        'verified_at',
        'validated_at',
        'paid_off_at',
        'accepted_at',
    ];

    protected function casts(): array
    {
        return [
            'registered_at' => 'datetime',
            'verified_at' => 'datetime',
            'validated_at' => 'datetime',
            'paid_off_at' => 'datetime',
            'accepted_at' => 'datetime',
        ];
    }

    public function wave(): BelongsTo
    {
        return $this->belongsTo(Wave::class)->withDefault();
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class)->withDefault();
    }

    public function profile(): BelongsTo
    {
        return $this->belongsTo(UserProfile::class, 'user_id', 'user_id')->withDefault();
    }

    public function files(): HasMany
    {
        return $this->hasMany(UserFile::class, 'user_id', 'user_id');
    }

    public function registeredBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'registered_by')->withDefault();;
    }

    public function bills(): HasMany
    {
        return $this->hasMany(RegistrantBill::class, 'registrant_id');
    }

    public function payments(): HasMany
    {
        return $this->hasMany(RegistrantPayment::class);
    }

    public function note(): MorphOne
    {
        return $this->morphOne(Note::class, 'noteable');
    }

    public function notes(): MorphMany
    {
        return $this->morphMany(Note::class, 'noteable');
    }
}
