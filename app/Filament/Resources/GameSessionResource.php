<?php

namespace App\Filament\Resources;

use App\Filament\Resources\GameSessionResource\Pages;
use App\Filament\Resources\GameSessionResource\RelationManagers;
use App\Models\GameSession;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class GameSessionResource extends Resource
{
    protected static ?string $model = GameSession::class;

    protected static ?string $navigationIcon = 'heroicon-o-play';
    
    protected static ?string $navigationLabel = 'Sessions de Jeu';
    
    protected static ?string $modelLabel = 'Session de Jeu';
    
    protected static ?string $pluralModelLabel = 'Sessions de Jeu';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->label('Nom de la session')
                    ->required()
                    ->maxLength(255),
                Forms\Components\Select::make('status')
                    ->label('Statut')
                    ->options([
                        'waiting' => 'En attente',
                        'playing' => 'En cours',
                        'finished' => 'Terminée',
                    ])
                    ->required(),
                Forms\Components\TextInput::make('min_players')
                    ->label('Joueurs minimum')
                    ->numeric()
                    ->default(2)
                    ->minValue(2)
                    ->maxValue(6),
                Forms\Components\TextInput::make('max_players')
                    ->label('Joueurs maximum')
                    ->numeric()
                    ->default(2)
                    ->minValue(2)
                    ->maxValue(6),
                Forms\Components\DateTimePicker::make('started_at')
                    ->label('Commencée le'),
                Forms\Components\DateTimePicker::make('finished_at')
                    ->label('Terminée le'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->label('Nom de la session')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\BadgeColumn::make('status')
                    ->label('Statut')
                    ->colors([
                        'warning' => 'waiting',
                        'success' => 'playing',
                        'danger' => 'finished',
                    ]),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Créée le')
                    ->dateTime('d/m/Y H:i')
                    ->sortable(),
                Tables\Columns\TextColumn::make('started_at')
                    ->label('Commencée le')
                    ->dateTime('d/m/Y H:i')
                    ->sortable()
                    ->toggleable(),
                Tables\Columns\TextColumn::make('finished_at')
                    ->label('Terminée le')
                    ->dateTime('d/m/Y H:i')
                    ->sortable()
                    ->toggleable(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->label('Statut')
                    ->options([
                        'waiting' => 'En attente',
                        'playing' => 'En cours',
                        'finished' => 'Terminée',
                    ]),
            ])
            ->actions([
                Tables\Actions\ViewAction::make()
                    ->iconButton(),
                Tables\Actions\EditAction::make()
                    ->iconButton()
                    ->successNotificationTitle('Session modifiée avec succès'),
                Tables\Actions\DeleteAction::make()
                    ->iconButton()
                    ->successNotificationTitle('Session supprimée avec succès'),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])
            ->defaultSort('created_at', 'desc');
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListGameSessions::route('/'),
            'create' => Pages\CreateGameSession::route('/create'),
            'view' => Pages\ViewGameSession::route('/{record}'),
            'edit' => Pages\EditGameSession::route('/{record}/edit'),
        ];
    }
}
