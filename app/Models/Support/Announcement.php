<?php

namespace App\Models\Support;

use App\Enums\Support\AnnouncementPlacement;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Announcement extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'title',
        'content',
        'placement',
        'published_at',
        'expired_at',
    ];

    protected $casts = [
        'placement' => AnnouncementPlacement::class,
        'published_at' => 'datetime',
        'expired_at' => 'datetime',
    ];

    public function scopeActive($query): Builder
    {
        return $query->where('published_at', '<=', now())->where(function ($query) {
            $query->where('expired_at', '>=', now())->orWhereNull('expired_at');
        });
    }
}
