<?php

namespace App\Models;

use App\Enums\Sex;
use App\Enums\Religion;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class UserProfile extends Model
{
    protected $table = 'user_profile';

    protected $primaryKey = 'user_id';

    protected $fillable = [
        'pob',
        'dob',
        'sex',
        'nisn',
        'kk_number',
        'is_alive',
        'siblings_count',
        'child_order',
        'religion',
        'aspiration',
        'last_education',
        'monthly_income',
        'nationality'
    ];

    protected function casts(): array
    {
        return [
            'dob' => 'datetime',
            'sex' => Sex::class,
            'religion' => Religion::class,
            'is_alive' => 'boolean',
            'siblings_count' => 'integer',
            'child_order' => 'integer',
            'aspiration' => 'array',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
