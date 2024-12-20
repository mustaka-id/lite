<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Period extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'year_id',
        'name',
    ];

    public function year(): BelongsTo
    {
        return $this->belongsTo(Year::class);
    }
}
