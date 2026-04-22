<?php

namespace App\Filament\Resources\CafeTables\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class CafeTableForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->label('Table Name or Number')
                    ->placeholder('e.g., 101, 102, VIP 1')
                    ->required()
                    ->unique(ignoreRecord: true) 
                    ->maxLength(255),
            ]);
    }
}
