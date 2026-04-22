<?php

namespace App\Filament\Resources\Bookings\Schemas;

use App\Models\Booking;
use App\Models\Room;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\TimePicker;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Components\Utilities\Set;
use Filament\Schemas\Schema;

class BookingForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Booking Details')
                    ->schema([
                        Select::make('room_id')
                            ->label('Select Room')
                            ->relationship('room', 'name')
                            ->required()
                            ->reactive()
                            ->afterStateUpdated(function (Set $set, $state) {
                                $room = Room::find($state);
                                if (! $room) {
                                    return;
                                }

                                if ($room->slug === 'vip') {
                                    $set('total_price', $room->base_cost);
                                    $set('total_person', null);
                                } elseif ($room->slug === 'dnd') {
                                    $set('total_price', 0);
                                }
                            })
                            ->columnSpanFull(),

                        TextInput::make('customer_name')
                            ->label('Customer Name')
                            ->required()
                            ->maxLength(255)
                            ->columnSpanFull(),

                        TextInput::make('customer_phone')
                            ->label('Customer Phone')
                            ->tel()
                            ->required()
                            ->maxLength(20),

                        DatePicker::make('booking_date')
                            ->label('Booking Date')
                            ->required()
                            ->native(false)
                            ->minDate(\Carbon\Carbon::now()->startOfDay())
                            ->displayFormat('d M Y')
                            ->disabledDates(function (Get $get) {
                                $roomId = $get('room_id');
                                if (! $roomId) {
                                    return [];
                                }

                                return Booking::query()
                                    ->where('room_id', $roomId)
                                    ->whereIn('status', ['confirmed', 'pending'])
                                    ->pluck('booking_date')
                                    ->toArray();
                            })
                            ->live(),

                        TimePicker::make('eta')
                            ->label('ETA (Arrival Time)')
                            ->required()
                            ->native(false)
                            ->seconds(false),

                        TextInput::make('notes')
                            ->label('Notes'),
                    ])->columns(2),

                Section::make('D&D Session Details')
                    ->schema([
                        TextInput::make('total_person')
                            ->label('Total Person (85k/pax)')
                            ->numeric()
                            ->required()
                            ->minValue(1)
                            ->live(onBlur: true)
                            ->afterStateUpdated(function (Set $set, Get $get, $state) {
                                $roomId = $get('room_id');
                                if ($roomId && $state) {
                                    $room = Room::find($roomId);
                                    if ($room && $room->slug === 'dnd') {
                                        $set('total_price', $state * $room->person_cost);
                                    }
                                }
                            }),

                        Toggle::make('need_dm')
                            ->label('Need Dungeon Master?')
                            ->inline(false)
                            ->default(false),
                    ])
                    ->visible(function (Get $get) {
                        $roomId = $get('room_id');
                        if (! $roomId) {
                            return false;
                        }
                        $room = Room::find($roomId);

                        return $room && $room->slug === 'dnd';
                    })
                    ->columns(2),

                Section::make('Payment Details')
                    ->schema([
                        TextInput::make('total_price')
                            ->label('Total to Collect')
                            ->prefix('Rp')
                            ->numeric()
                            ->readOnly()
                            ->required(),

                        Select::make('status')
                            ->options([
                                'pending' => 'Pending (Waiting for Payment)',
                                'confirmed' => 'Confirmed (Booked)',
                                'cancelled' => 'Cancelled',
                                'completed' => 'Completed',
                            ])
                            ->default('pending')
                            ->required(),

                        Select::make('payment_status')
                            ->options([
                                'unpaid' => 'Unpaid',
                                'paid' => 'Paid',
                            ])
                            ->default('unpaid')
                            ->required(),

                        FileUpload::make('payment_proof')
                            ->label('Payment Screenshot')
                            ->directory('receipts')
                            ->disk('public')
                            ->image()
                            ->openable()
                            ->downloadable()
                            ->columnSpanFull(),
                    ])
                    ->columns(2),
            ]);
    }
}
