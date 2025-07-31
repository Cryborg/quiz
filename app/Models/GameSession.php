<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class GameSession extends Model
{
    protected $fillable = [
        'name',
        'min_players',
        'max_players',
        'status',
        'selected_themes',
        'started_at',
        'finished_at',
    ];

    protected $casts = [
        'selected_themes' => 'array',
        'started_at' => 'datetime',
        'finished_at' => 'datetime',
    ];

    public function players(): HasMany
    {
        return $this->hasMany(Player::class);
    }

    // Méthode pour récupérer les thèmes sélectionnés
    public function getSelectedThemes()
    {
        if (!empty($this->selected_themes)) {
            return Theme::whereIn('id', $this->selected_themes)->withCount('questions')->get();
        }
        return collect();
    }

}
