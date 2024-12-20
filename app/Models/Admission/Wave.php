<?php

namespace App\Models\Admission;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Wave extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'adm_waves';

    protected $fillable = [
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
}
