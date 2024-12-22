<?php

namespace App\Models\Support;

use Illuminate\Database\Eloquent\Model;

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
        'village',
        'district',
        'regency',
        'province',
        'country',
        'zip_code',
        'latitude',
        'longitude',
    ];

    public function addressable()
    {
        return $this->morphTo();
    }
}
