<?php

namespace App\Filament\Widgets;

use App\Models\GameSession;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;

class RecentSessionsWidget extends BaseWidget
{
    protected int | string | array $columnSpan = 'full';
    
    protected static ?string $heading = 'Parties Récentes';

    public function table(Table $table): Table
    {
        return $table
            ->query(
                GameSession::query()
                    ->withCount('players')
                    ->latest('created_at')
                    ->limit(10)
            )
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->label('Nom de la Partie')
                    ->searchable()
                    ->limit(40),
                    
                Tables\Columns\BadgeColumn::make('status')
                    ->label('Statut')
                    ->colors([
                        'warning' => 'waiting',
                        'success' => 'playing',
                        'danger' => 'finished',
                    ])
                    ->formatStateUsing(fn (string $state): string => match ($state) {
                        'waiting' => 'En attente',
                        'playing' => 'En cours',
                        'finished' => 'Terminée',
                        default => $state,
                    }),
                    
                Tables\Columns\TextColumn::make('players_count')
                    ->label('Joueurs')
                    ->badge()
                    ->color('info'),
                    
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Créée le')
                    ->dateTime('d/m/Y H:i')
                    ->since()
                    ->sortable(),
                    
                Tables\Columns\TextColumn::make('started_at')
                    ->label('Commencée')
                    ->dateTime('d/m/Y H:i')
                    ->placeholder('Pas encore')
                    ->toggleable(),
            ])
            ->actions([
                Tables\Actions\ViewAction::make()
                    ->iconButton()
                    ->url(fn (GameSession $record): string => route('filament.admin.resources.game-sessions.view', $record)),
            ]);
    }
}