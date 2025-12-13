<?php

namespace App\Filament\Resources\MenuItems\Tables;

use App\Models\MenuItem;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Tables\Table;

class MenuItemsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                ImageColumn::make('image_path')
                    ->label('Image')
                    ->disk('public')
                    ->square(),

                TextColumn::make('name')
                    ->searchable()
                    ->description(fn (MenuItem $record) => $record->category->name),

                TextColumn::make('price')
                    ->money('IDR')
                    ->color(fn (MenuItem $record) => $record->has_discount ? 'danger' : null)
                    ->description(fn (MenuItem $record) => $record->has_discount ? 'Was: '.number_format($record->price) : null)
                    ->state(fn (MenuItem $record) => $record->has_discount ? $record->discount_price : $record->price),

                IconColumn::make('is_available')
                    ->boolean()
                    ->label('Stock'),

                IconColumn::make('is_bestseller')
                    ->boolean()
                    ->label('Top')
                    ->trueColor('warning'),
            ])
            ->filters([
                SelectFilter::make('category')->relationship('category', 'name'),
                TernaryFilter::make('is_available')->label('Stock Status'),
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
