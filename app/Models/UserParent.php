<?php

namespace App\Models;

use App\Enums\Parentship\ParentType;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\Pivot;

class UserParent extends Pivot
{
    protected $fillable = [
        'type',
        'user_id',
        'parent_id',
    ];

    protected function casts(): array
    {
        return [
            'type' => ParentType::class,
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function parent(): BelongsTo
    {
        return $this->belongsTo(User::class, 'parent_id');
    }

    public function childrens(): HasMany
    {
        return $this->hasMany(self::class, 'parent_id', 'user_id');
    }
}
