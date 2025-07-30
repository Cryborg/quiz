<?php

namespace App\Filament\Widgets;

use App\Models\GameSession;
use App\Models\Question;
use App\Models\Theme;
use App\Models\Player;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class GameStatsWidget extends BaseWidget
{
    protected function getStats(): array
    {
        $totalSessions = GameSession::count();
        $totalQuestions = Question::where('is_active', true)->count();
        $totalThemes = Theme::where('is_active', true)->count();
        $sessionsInProgress = GameSession::where('status', 'playing')->count();
        
        return [
            Stat::make('Total des Parties', $totalSessions)
                ->description('Parties créées')
                ->descriptionIcon('heroicon-m-play')
                ->color('success'),
                
            Stat::make('Parties en Cours', $sessionsInProgress)
                ->description('Actuellement jouées')
                ->descriptionIcon('heroicon-m-clock')
                ->color('warning'),
                
            Stat::make('Questions Actives', $totalQuestions)
                ->description('Prêtes à jouer')
                ->descriptionIcon('heroicon-m-question-mark-circle')
                ->color('info'),
                
            Stat::make('Thèmes Actifs', $totalThemes)
                ->description('Disponibles')
                ->descriptionIcon('heroicon-m-tag')
                ->color('primary'),
        ];
    }
}