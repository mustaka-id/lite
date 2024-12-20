<?php

namespace App\Models\Admission;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
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
        return $this->belongsTo(Wave::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function registeredBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'registered_by');
    }

    public function bills(): HasMany
    {
        return $this->hasMany(RegistrantBill::class);
    }
}
