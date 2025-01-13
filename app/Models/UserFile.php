<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Storage;

class UserFile extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'user_id',
        'category',
        'name',
        'path',
        'required'
    ];

    protected function pathUrl(): Attribute
    {
        return Attribute::make(
            get: fn() => filled($this->path) && Storage::exists($this->path) ? Storage::url($this->path) : null,
        );
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
