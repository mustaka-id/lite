<?php

namespace App\Models\Admission;

use App\Enums\PaymentMethod;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class RegistrantPayment extends Model
{
    use HasFactory;

    protected $table = 'adm_registrant_payments';

    protected $fillable = [
        'registrant_id',
        'bill_id',
        'code',
        'name',
        'amount',
        'method',
        'paid_at',
        'payer_id',
        'reciever_id',
    ];

    protected function casts(): array
    {
        return [
            'amount' => 'double',
            'method' => PaymentMethod::class,
            'paid_at' => 'datetime',
        ];
    }

    public function registrant(): BelongsTo
    {
        return $this->belongsTo(Registrant::class);
    }

    public function bill(): BelongsTo
    {
        return $this->belongsTo(RegistrantBill::class);
    }
}
