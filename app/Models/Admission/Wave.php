<?php

namespace App\Models\Admission;

use App\Models\Year;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Wave extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'adm_waves';

    protected $fillable = [
        'year_id',
        'name',
        'opened_at',
        'closed_at',
        'meta',
    ];

    protected function casts(): array
    {
        return [
            'opened_at' => 'datetime',
            'closed_at' => 'datetime',
            'meta' => 'array',
        ];
    }

    public function registrants(): HasMany
    {
        return $this->hasMany(Registrant::class);
    }

    public function registrantBills(): HasMany
    {
        return $this->hasMany(RegistrantBill::class);
    }

    public function year(): BelongsTo
    {
        return $this->belongsTo(Year::class);
    }

    public function scopeOpened($query): Builder
    {
        return $query->where('opened_at', '<=', now())->where(function ($query) {
            $query->where('closed_at', '>=', now())->orWhereNull('closed_at');
        });
    }
}
