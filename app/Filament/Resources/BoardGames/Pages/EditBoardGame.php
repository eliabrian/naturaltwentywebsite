<?php

namespace App\Filament\Resources\BoardGames\Pages;

use App\Filament\Resources\BoardGames\BoardGameResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditBoardGame extends EditRecord
{
    protected static string $resource = BoardGameResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
