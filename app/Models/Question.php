<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Question extends Model
{
    protected $fillable = [
        'theme_id',
        'question',
        'penalty_answer',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function theme(): BelongsTo
    {
        return $this->belongsTo(Theme::class);
    }

    public function answers(): HasMany
    {
        return $this->hasMany(Answer::class);
    }
}
