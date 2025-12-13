<?php

namespace App\Filament\Resources\Bookings\Tables;

use App\Models\Booking;
use Carbon\Carbon;
use Filament\Actions\Action;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\DatePicker;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class BookingsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('booking_date')
                    ->date('d M Y')
                    ->sortable(),

                TextColumn::make('room.name')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'VIP Room' => 'warning',
                        'D&D Room' => 'info',
                        default => 'gray',
                    }),

                TextColumn::make('customer_name')
                    ->searchable(),

                TextColumn::make('status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'confirmed' => 'success',
                        'pending' => 'warning',
                        'cancelled' => 'danger',
                        default => 'gray',
                    }),

                TextColumn::make('total_price')
                    ->money('IDR'),

                IconColumn::make('payment_status')
                    ->icon(fn (string $state): string => match ($state) {
                        'paid' => 'heroicon-o-check-circle',
                        'unpaid' => 'heroicon-o-x-circle',
                    })
                    ->color(fn (string $state): string => match ($state) {
                        'paid' => 'success',
                        'unpaid' => 'danger',
                    }),
            ])
            ->defaultSort('booking_date', 'desc')
            ->filters([
                SelectFilter::make('room')
                    ->relationship('room', 'name'),
                SelectFilter::make('status')
                    ->options([
                        'pending' => 'Pending',
                        'confirmed' => 'Confirmed',
                        'cancelled' => 'Cancelled',
                    ]),
                Filter::make('booking_date')
                    ->schema([
                        DatePicker::make('date_from')
                            ->label('From Date'),
                        DatePicker::make('date_until')
                            ->label('Until Date'),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query
                            ->when(
                                $data['date_from'],
                                fn (Builder $query, $date): Builder => $query->whereDate('booking_date', '>=', $date),
                            )
                            ->when(
                                $data['date_until'],
                                fn (Builder $query, $date): Builder => $query->whereDate('booking_date', '<=', $date),
                            );
                    })
                    ->indicateUsing(function (array $data): array {
                        $indicators = [];
                        if ($data['date_from'] ?? null) {
                            $indicators['date_from'] = 'From '.Carbon::parse($data['date_from'])->toFormattedDateString();
                        }
                        if ($data['date_until'] ?? null) {
                            $indicators['date_until'] = 'Until '.Carbon::parse($data['date_until'])->toFormattedDateString();
                        }

                        return $indicators;
                    }),
            ])
            ->recordActions([
                EditAction::make(),
                Action::make('approve_and_notify')
                    ->label('Approve & Message')
                    ->icon('heroicon-o-check-circle')
                    ->color('success')
                    ->requiresConfirmation()
                    ->modalHeading('Approve Booking')
                    ->modalDescription('This will mark the booking as confirmed/paid and open WhatsApp to notify the customer.')
                    ->action(function (Booking $record) {
                        $record->update([
                            'status' => 'confirmed',
                            'payment_status' => 'paid',
                        ]);
                    })
                    ->after(function (Booking $record) {
                        $date = Carbon::parse($record->booking_date)->format('d M Y');
                        $roomName = $record->room->name;
                        $price = number_format($record->total_price, 0, ',', '.');

                        $message = "Hello Traveller! 👋\n\n"
                            ."Your booking for *{$roomName}* on *{$date}* at *{$record->eta}* is CONFIRMED! ✅\n\n"
                            ."Payment received: Rp {$price}.\n"
                            .'Thank you, see you soon!';

                        $encodedMessage = urlencode($message);

                        $phone = $record->customer_phone;
                        if (str_starts_with($phone, '0')) {
                            $phone = '62'.substr($phone, 1);
                        }

                        return redirect()->to("https://wa.me/{$phone}?text={$encodedMessage}");
                    })
                    ->visible(fn (Booking $record) => $record->status === 'pending'),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
