<?php

namespace App\Filament\Resources\QuestionResource\RelationManagers;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;

class AnswersRelationManager extends RelationManager
{
    protected static string $relationship = 'answers';

    protected static ?string $title = 'Réponses Correctes';

    protected static ?string $modelLabel = 'Réponse';

    protected static ?string $pluralModelLabel = 'Réponses';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('answer')
                    ->label('Réponse')
                    ->required()
                    ->maxLength(255),
                    
                Forms\Components\Select::make('points')
                    ->label('Points')
                    ->options([
                        1 => '1 point (facile)',
                        2 => '2 points (moyen)',
                        3 => '3 points (difficile)',
                        4 => '4 points (très difficile)',
                        5 => '5 points (exceptionnel)',
                    ])
                    ->required()
                    ->default(1),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('answer')
            ->columns([
                Tables\Columns\TextInputColumn::make('answer')
                    ->label('Réponse')
                    ->searchable()
                    ->sortable()
                    ->afterStateUpdated(function () {
                        \Filament\Notifications\Notification::make()
                            ->title('Réponse modifiée avec succès')
                            ->icon('heroicon-o-pencil-square')
                            ->success()
                            ->send();
                    }),
                    
                Tables\Columns\SelectColumn::make('points')
                    ->label('Points')
                    ->options([
                        1 => '1',
                        2 => '2',
                        3 => '3',
                        4 => '4',
                        5 => '5',
                    ])
                    ->sortable()
                    ->afterStateUpdated(function () {
                        \Filament\Notifications\Notification::make()
                            ->title('Points modifiés avec succès')
                            ->icon('heroicon-o-star')
                            ->success()
                            ->send();
                    }),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('points')
                    ->options([
                        1 => '1 point',
                        2 => '2 points',
                        3 => '3 points',
                        4 => '4 points',
                        5 => '5 points',
                    ]),
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make()
                    ->label('Ajouter une réponse')
                    ->successNotificationTitle('Réponse ajoutée avec succès'),
            ])
            ->actions([
                Tables\Actions\EditAction::make()
                    ->iconButton()
                    ->successNotificationTitle('Réponse modifiée avec succès'),
                Tables\Actions\DeleteAction::make()
                    ->iconButton()
                    ->successNotificationTitle('Réponse supprimée avec succès'),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])
            ->defaultSort('points', 'asc');
    }
}