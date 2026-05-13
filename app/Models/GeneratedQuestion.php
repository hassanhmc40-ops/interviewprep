<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class GeneratedQuestion extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $fillable = [
        'concept_id',
        'question',
        'generated_at',
    ];

    protected $casts = [
        'question' => 'array',
        'generated_at' => 'datetime',
    ];

    public function concept(): BelongsTo
    {
        return $this->belongsTo(Concept::class);
    }
}