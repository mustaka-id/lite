<?php

namespace App\Models\Admission;

use App\Models\Support\Note;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphOne;
use Illuminate\Database\Eloquent\SoftDeletes;

class RegistrantBill extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'adm_registrant_bills';

    protected $fillable = [
        'registrant_id',
        'name',
        'meta'
    ];

    protected function casts(): array
    {
        return [
            'meta' => 'array',
        ];
    }

    public function registrant(): BelongsTo
    {
        return $this->belongsTo(Registrant::class)->withDefault();
    }

    public function items(): HasMany
    {
        return $this->hasMany(RegistrantBillItem::class, 'bill_id');
    }

    public function payments(): HasMany
    {
        return $this->hasMany(RegistrantPayment::class, 'bill_id');
    }

    public function note(): MorphOne
    {
        return $this->morphOne(Note::class, 'noteable');
    }
}
