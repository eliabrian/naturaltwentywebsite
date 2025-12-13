<?php

namespace App\Filament\Resources\BoardGames\Tables;

use App\Models\BoardGame;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Support\Str;

class BoardGamesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                ImageColumn::make('cover_image')
                    ->disk('public')
                    ->square(),

                TextColumn::make('title')
                    ->searchable()
                    ->sortable()
                    ->weight('bold')
                    ->description(fn (BoardGame $record) => Str::limit($record->description, 30)),

                TextColumn::make('categories.name')
                    ->badge()
                    ->color('info')
                    ->separator(','),

                TextColumn::make('players')
                    ->label('Players')
                    ->getStateUsing(fn (BoardGame $record) => "{$record->min_players}-{$record->max_players}"),

                TextColumn::make('complexity')
                    ->numeric()
                    ->sortable()
                    ->icon('heroicon-s-star')
                    ->iconColor('warning'),

                TextColumn::make('status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'available' => 'success',
                        'maintenance' => 'warning',
                        'missing_parts' => 'danger',
                    }),

                TextColumn::make('shelf_location')
                    ->label('Shelf')
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                SelectFilter::make('status')
                    ->options([
                        'available' => 'Available',
                        'maintenance' => 'Maintenance',
                    ]),

                SelectFilter::make('categories')
                    ->relationship('categories', 'name'),
            ])
            ->recordActions([
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
