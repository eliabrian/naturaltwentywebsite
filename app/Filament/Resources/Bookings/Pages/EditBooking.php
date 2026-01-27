<?php

namespace App\Filament\Resources\Bookings\Pages;

use App\Filament\Resources\Bookings\BookingResource;
use Carbon\Carbon;
use Filament\Actions\Action;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditBooking extends EditRecord
{
    protected static string $resource = BookingResource::class;

    protected function getHeaderActions(): array
    {
        $date = Carbon::parse($this->record->booking_date)->format('d M Y');
        $roomName = $this->record->room->name;
        $price = number_format($this->record->room->deposit, 0, ',', '.');
        $phone = $this->record->customer_phone;
        if (str_starts_with($phone, '0')) {
            $phone = '62'.substr($phone, 1);
        }

        $message = "Hello Traveller! 👋\n\n"
            ."Your booking for *{$roomName}* on *{$date}* at *{$this->record->eta}* is CONFIRMED! ✅\n\n"
            ."Payment received: Rp {$price}.\n"
            .'Thank you, see you soon!';

        $encodedMessage = urlencode($message);

        return [
            Action::make('whatsapp')
                ->label('WhatsApp Message')
                ->icon('heroicon-o-chat-bubble-left')
                ->color('success')
                ->url(fn ($record) => "https://wa.me/{$phone}?text={$encodedMessage}")
                ->openUrlInNewTab(),
            DeleteAction::make(),
        ];
    }
}
