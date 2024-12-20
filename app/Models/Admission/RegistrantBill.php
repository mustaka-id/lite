<?php

namespace App\Models\Admission;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RegistrantBill extends Model
{
    use HasFactory;

    protected $table = 'adm_registrant_bills';

    public function buildSortQuery()
    {
        return static::query()->where('registrant_id', $this->registrant_id)->where('category', $this->category);
    }
}
