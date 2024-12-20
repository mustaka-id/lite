<?php

namespace App\Models;

use App\Models\Admission\Wave;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Year extends Model
{
    use SoftDeletes;

    protected $fillable = ['name'];

    public function periods(): HasMany
    {
        return $this->hasMany(Period::class);
    }

    public function waves(): HasMany
    {
        return $this->hasMany(Wave::class);
    }
}
