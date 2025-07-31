<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Player extends Model
{
    protected $fillable = [
        'game_session_id',
        'name',
        'score',
        'answered_questions',
    ];

    protected $casts = [
        'answered_questions' => 'array',
    ];


    public function gameSession(): BelongsTo
    {
        return $this->belongsTo(GameSession::class);
    }
}
