<?php

namespace App\Filament\Resources\BoardGames;

use App\Filament\Resources\BoardGames\Pages\CreateBoardGame;
use App\Filament\Resources\BoardGames\Pages\EditBoardGame;
use App\Filament\Resources\BoardGames\Pages\ListBoardGames;
use App\Filament\Resources\BoardGames\Schemas\BoardGameForm;
use App\Filament\Resources\BoardGames\Tables\BoardGamesTable;
use App\Models\BoardGame;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use UnitEnum;

class BoardGameResource extends Resource
{
    protected static ?string $model = BoardGame::class;

    protected static string|UnitEnum|null $navigationGroup = 'Library';

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedPuzzlePiece;

    protected static ?string $recordTitleAttribute = 'name';

    public static function form(Schema $schema): Schema
    {
        return BoardGameForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return BoardGamesTable::configure($table);
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
            'index' => ListBoardGames::route('/'),
            'create' => CreateBoardGame::route('/create'),
            'edit' => EditBoardGame::route('/{record}/edit'),
        ];
    }
}
