<?php

namespace App\Models\Admission;

use Illuminate\Database\Eloquent\Model;
use App\Models\Admission\RegistrantBill;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Spatie\EloquentSortable\SortableTrait;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class RegistrantBillItem extends Model
{
    use HasFactory, SoftDeletes, SortableTrait;

    protected $table = 'adm_registrant_bill_items';

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

    public function bill(): BelongsTo
    {
        return $this->belongsTo(RegistrantBill::class, 'bill_id')->withDefault();
    }

    public function buildSortQuery()
    {
        return static::query()->where('bill_id', $this->bill_id);
    }
}
