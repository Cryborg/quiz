<?php

namespace App\Filament\Resources;

use App\Filament\Resources\QuestionResource\Pages;
use App\Filament\Resources\QuestionResource\RelationManagers;
use App\Models\Question;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class QuestionResource extends Resource
{
    protected static ?string $model = Question::class;

    protected static ?string $navigationIcon = 'heroicon-o-question-mark-circle';
    
    protected static ?string $navigationLabel = 'Questions';
    
    protected static ?string $modelLabel = 'Question';
    
    protected static ?string $pluralModelLabel = 'Questions';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('theme_id')
                    ->label('Thème')
                    ->relationship('theme', 'name')
                    ->required()
                    ->searchable(),
                Forms\Components\Textarea::make('question')
                    ->label('Question')
                    ->required()
                    ->maxLength(500)
                    ->rows(3),
                Forms\Components\TextInput::make('penalty_answer')
                    ->label('Réponse piège (-5 points)')
                    ->required()
                    ->maxLength(255)
                    ->helperText('Cette réponse piège retire 5 points si elle est sélectionnée'),
                Forms\Components\Toggle::make('is_active')
                    ->label('Question active')
                    ->default(true),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('question')
                    ->label('Question')
                    ->searchable()
                    ->limit(50)
                    ->tooltip(function (Tables\Columns\TextColumn $column): ?string {
                        $state = $column->getState();
                        if (strlen($state) <= 50) {
                            return null;
                        }
                        return $state;
                    }),
                Tables\Columns\TextColumn::make('theme.name')
                    ->label('Thème')
                    ->sortable()
                    ->searchable(),
                Tables\Columns\ToggleColumn::make('is_active')
                    ->label('Active')
                    ->afterStateUpdated(function ($record, $state) {
                        \Filament\Notifications\Notification::make()
                            ->title('Question ' . ($state ? 'activée' : 'désactivée'))
                            ->icon($state ? 'heroicon-o-check-circle' : 'heroicon-o-x-circle')
                            ->color($state ? 'success' : 'warning')
                            ->send();
                    }),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('theme')
                    ->relationship('theme', 'name')
                    ->label('Thème'),
                Tables\Filters\TernaryFilter::make('is_active')
                    ->label('Active')
                    ->boolean()
                    ->trueLabel('Questions actives')
                    ->falseLabel('Questions inactives')
                    ->native(false),
            ])
            ->actions([
                Tables\Actions\ViewAction::make()
                    ->iconButton(),
                Tables\Actions\EditAction::make()
                    ->iconButton()
                    ->successNotificationTitle('Question modifiée avec succès'),
                Tables\Actions\DeleteAction::make()
                    ->iconButton()
                    ->successNotificationTitle('Question supprimée avec succès'),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])
            ->defaultSort('theme_id', 'asc');
    }

    public static function getRelations(): array
    {
        return [
            RelationManagers\AnswersRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListQuestions::route('/'),
            'create' => Pages\CreateQuestion::route('/create'),
            'view' => Pages\ViewQuestion::route('/{record}'),
            'edit' => Pages\EditQuestion::route('/{record}/edit'),
        ];
    }
}
