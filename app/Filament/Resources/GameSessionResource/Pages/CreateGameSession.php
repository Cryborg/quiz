<?php

namespace App\Filament\Resources\GameSessionResource\Pages;

use App\Filament\Resources\GameSessionResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateGameSession extends CreateRecord
{
    protected static string $resource = GameSessionResource::class;
    
    protected function getCreatedNotificationTitle(): ?string
    {
        return 'Session créée avec succès';
    }
}
