<?php

namespace App\Filament\Resources\BoardGames\Pages;

use App\Filament\Resources\BoardGames\BoardGameResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListBoardGames extends ListRecords
{
    protected static string $resource = BoardGameResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
