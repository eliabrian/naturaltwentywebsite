<?php

namespace App\Filament\Resources\BoardGames\Schemas;

use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Group;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Utilities\Set;
use Filament\Schemas\Schema;
use Illuminate\Support\Str;

class BoardGameForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Group::make()->schema([
                    Section::make('Game Details')->schema([
                        TextInput::make('title')
                            ->required()
                            ->live(onBlur: true)
                            ->afterStateUpdated(fn (string $operation, $state, Set $set) => $operation === 'create' ? $set('slug', Str::slug($state)) : null
                            ),

                        TextInput::make('slug')
                            ->required()
                            ->unique(ignoreRecord: true),

                        Select::make('categories')
                            ->relationship('categories', 'name')
                            ->multiple()
                            ->preload()
                            ->searchable(),

                        Textarea::make('description')
                            ->rows(3)
                            ->columnSpanFull(),
                    ]),

                    Section::make('Visuals')->schema([
                        FileUpload::make('cover_image')
                            ->directory('boardgame')
                            ->disk('public')
                            ->image()
                            ->openable()
                            ->downloadable()
                            ->columnSpanFull(),
                    ]),
                ])->columnSpan(2),

                Group::make()->schema([
                    Section::make('Stats')->schema([
                        Grid::make(2)->schema([
                            TextInput::make('min_players')
                                ->label('Min Players')
                                ->numeric()
                                ->default(2),

                            TextInput::make('max_players')
                                ->label('Max Players')
                                ->numeric()
                                ->default(4),
                        ]),

                        Grid::make(2)->schema([
                            TextInput::make('playtime_min')
                                ->label('Min Mins')
                                ->numeric(),

                            TextInput::make('playtime_max')
                                ->label('Max Mins')
                                ->numeric(),
                        ]),

                        Select::make('complexity')
                            ->options([
                                1 => '1 - Very Easy / Party',
                                2 => '2 - Easy / Family',
                                3 => '3 - Medium / Strategy',
                                4 => '4 - Hard / Expert',
                                5 => '5 - Very Hard',
                            ])
                            ->required()
                            ->default(2),
                    ]),

                    Section::make('Cafe Logistics')->schema([
                        TextInput::make('shelf_location')
                            ->placeholder('e.g. A-4')
                            ->label('Location'),

                        Select::make('status')
                            ->options([
                                'available' => 'Available',
                                'maintenance' => 'Maintenance (Broken)',
                                'missing_parts' => 'Missing Parts',
                            ])
                            ->default('available')
                            ->required()
                            ->native(false),
                    ]),
                ])->columnSpan(1),
            ])->columns(3);
    }
}
