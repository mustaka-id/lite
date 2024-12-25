<?php

namespace App\Observers\Support;

use Indonesia;
use App\Models\Support\Address;

class AddressObserver
{
    public function creating(Address $address)
    {
        if ($address->village_id) {
            $village = Indonesia::findVillage($address->village_id, ['district.city.province']);
            $address->village = $village->name;
            $address->district = $village->district->name;
            $address->regency = $village->district->city->name;
            $address->province = $village->district->city->province->name;
        }
    }

    public function updating(Address $address)
    {
        $this->creating($address);
    }
}
