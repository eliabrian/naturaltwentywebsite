<?php

namespace App\Filament\Resources\MenuItems\Schemas;

use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Group;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class MenuItemForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Group::make()->schema([
                    Section::make('Item Details')->schema([
                        Select::make('menu_category_id')
                            ->relationship('category', 'name')
                            ->required()
                            ->createOptionForm([
                                TextInput::make('name')->required(),
                                TextInput::make('sort_order')->numeric()->required(),
                            ]),

                        TextInput::make('name')
                            ->required(),

                        Textarea::make('description')
                            ->rows(3),
                    ]),

                    Section::make('Pricing')->schema([
                        TextInput::make('price')
                            ->label('Original Price')
                            ->prefix('Rp')
                            ->numeric()
                            ->required(),

                        TextInput::make('discount_price')
                            ->label('Discounted Price (Optional)')
                            ->helperText('Leave empty if not on sale. Must be lower than Original Price.')
                            ->prefix('Rp')
                            ->numeric()
                            ->lte('price'),
                    ])->columns(2),
                ])->columnSpan(2),

                Group::make()->schema([
                    Section::make('Status')->schema([
                        Toggle::make('is_available')
                            ->label('In Stock')
                            ->default(true),

                        Toggle::make('is_bestseller')
                            ->label('Bestseller Badge')
                            ->onColor('warning'),

                        Select::make('station')
                            ->label('Preparation Station (Routing)')
                            ->options([
                                'kitchen' => '👨‍🍳 Kitchen (Food)',
                                'bar' => '🍹 Bar (Drinks)',
                            ])
                            ->default('kitchen')
                            ->required(),
                    ]),

                    Section::make('Photo')->schema([
                        FileUpload::make('image_path')
                            ->image()
                            ->directory('menu')
                            ->disk('public'),
                    ]),
                ])->columnSpan(1),
            ])->columns(3);
    }
}
