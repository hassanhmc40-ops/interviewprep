<?php

namespace App\Models;

use App\Enums\DifficultyEnum;
use App\Enums\StatusEnum;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Concept extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'domain_id',
        'user_id',
        'title',
        'explanation',
        'difficulty_level',
        'status',
    ];

    protected $casts = [
        'difficulty_level' => DifficultyEnum::class,
        'status' => StatusEnum::class,
    ];

    public function domain(): BelongsTo
    {
        return $this->belongsTo(Domain::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function generatedQuestions(): HasMany
    {
        return $this->hasMany(GeneratedQuestion::class);
    }

    public function getStatusLabelAttribute(): string
    {
        return $this->status->label();
    }

    public function getDifficultyLabelAttribute(): string
    {
        return $this->difficulty_level->label();
    }
}