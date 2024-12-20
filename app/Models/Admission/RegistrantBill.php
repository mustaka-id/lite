<?php

namespace App\Models\Admission;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Spatie\EloquentSortable\SortableTrait;

class RegistrantBill extends Model
{
    use HasFactory, SortableTrait;

    protected $table = 'adm_registrant_bills';

    protected $fillable = [
        'wave_id',
        'registrant_id',
        'category',
        'sequence',
        'name',
        'amount',
    ];

    protected function casts(): array
    {
        return [
            'amount' => 'double',
        ];
    }

    public function registrant(): BelongsTo
    {
        return $this->belongsTo(Registrant::class);
    }

    public function wave(): BelongsTo
    {
        return $this->belongsTo(Wave::class);
    }

    public function payments(): HasMany
    {
        return $this->hasMany(RegistrantPayment::class, 'bill_id');
    }

    public function buildSortQuery()
    {
        return static::query()->where('registrant_id', $this->registrant_id)->where('category', $this->category);
    }
}
