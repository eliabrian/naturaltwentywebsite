<?php

namespace App\Filament\Resources\BoardGames\Pages;

use App\Filament\Resources\BoardGames\BoardGameResource;
use Filament\Resources\Pages\CreateRecord;

class CreateBoardGame extends CreateRecord
{
    protected static string $resource = BoardGameResource::class;
}
