<?php

namespace App\Filament\Pages;

use Filament\Pages\Page;

class ReturnToGame extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-play';
    
    protected static string $view = 'filament.pages.return-to-game';
    
    protected static ?string $navigationLabel = 'Retourner au Jeu';
    
    protected static ?string $title = 'Retourner au Jeu';
    
    protected static ?int $navigationSort = 1;
    
    public function mount(): void
    {
        // Redirection immédiate vers le jeu
        $this->redirect('/');
    }
}