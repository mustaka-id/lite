<?php

namespace App\Models;

use App\Enums\Support\Grade;
use App\Enums\Support\GradeLevel;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Classroom extends Model
{
    /** @use HasFactory<\Database\Factories\ClassroomFactory> */
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'year_id',
        'grade',
        'level',
        'name',
        'capacity',
        'homeroom_id',
    ];

    protected function casts(): array
    {
        return [
            'grade' => Grade::class,
            'level' => GradeLevel::class,
        ];
    }

    public function year(): BelongsTo
    {
        return $this->belongsTo(Year::class);
    }

    public function homeroom(): BelongsTo
    {
        return $this->belongsTo(User::class, 'homeroom_id');
    }
}
