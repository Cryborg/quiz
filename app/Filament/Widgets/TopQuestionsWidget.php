<?php

namespace App\Filament\Widgets;

use App\Models\Question;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;
use Illuminate\Database\Eloquent\Builder;

class TopQuestionsWidget extends BaseWidget
{
    protected int | string | array $columnSpan = 'full';
    
    protected static ?string $heading = 'Questions les Plus Populaires';

    public function table(Table $table): Table
    {
        return $table
            ->query(
                Question::query()
                    ->with(['theme', 'answers'])
                    ->withCount('answers')
                    ->where('is_active', true)
                    ->orderBy('answers_count', 'desc')
                    ->limit(10)
            )
            ->columns([
                Tables\Columns\TextColumn::make('question')
                    ->label('Question')
                    ->limit(60)
                    ->tooltip(function (Tables\Columns\TextColumn $column): ?string {
                        $state = $column->getState();
                        if (strlen($state) <= 60) {
                            return null;
                        }
                        return $state;
                    }),
                    
                Tables\Columns\TextColumn::make('theme.name')
                    ->label('Thème')
                    ->badge()
                    ->color('primary'),
                    
                Tables\Columns\TextColumn::make('answers_count')
                    ->label('Nb Réponses')
                    ->badge()
                    ->color('success')
                    ->sortable(),
                    
                Tables\Columns\TextColumn::make('penalty_answer')
                    ->label('Réponse Piège')
                    ->limit(30)
                    ->color('danger'),
            ])
            ->actions([
                Tables\Actions\ViewAction::make()
                    ->iconButton()
                    ->url(fn (Question $record): string => route('filament.admin.resources.questions.view', $record)),
            ]);
    }
}