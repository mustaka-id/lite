<?php

namespace App\Models;

use App\Enums\Support\Grade;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class UserEducation extends Model
{
    use HasFactory;

    protected $table = 'user_educations';

    protected $fillable = [
        'user_id',
        'grade',
        'name',
        'npsn',
        'certificate',
        'certificate_number',
    ];

    protected function casts(): array
    {
        return [
            'grade' => Grade::class,
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
