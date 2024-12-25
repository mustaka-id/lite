<?php

namespace App\Models\Support;

use App\Observers\Support\AddressObserver;
use Illuminate\Database\Eloquent\Attributes\ObservedBy;
use Illuminate\Database\Eloquent\Model;

#[ObservedBy(AddressObserver::class)]
class Address extends Model
{
    protected $fillable = [
        'addressable_id',
        'addressable_type',
        'name',
        'primary',
        'secondary',
        'rt',
        'rw',
        'village_id',
        'village',
        'district',
        'regency',
        'province',
        'country',
        'zipcode',
        'lat',
        'long',
    ];

    public function addressable()
    {
        return $this->morphTo();
    }
}
