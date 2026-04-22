<?php

namespace App\Filament\Pages;

use App\Models\Order;
use BackedEnum;
use Filament\Pages\Page;
use Filament\Support\Icons\Heroicon;
use Livewire\Attributes\Computed;
use Livewire\Attributes\On;
use UnitEnum;

class KitchenDisplay extends Page
{
    protected string $view = 'filament.pages.kitchen-display';

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedFire;
    
    protected static ?string $navigationLabel = "Kitchen Display (KDS)";

    protected static ?string $title = 'Kitchen Display System';

    protected static string|UnitEnum|null $navigationGroup = "Store Operations";

    #[Computed]
    public function pendingOrders()
    {
        return Order::with(['items.menuItem', 'cafeTable'])
            ->where('status', 'pending')
            ->orderBy('created_at', 'asc')
            ->get();
    }

    #[Computed]
    public function preparingOrders()
    {
        return Order::with('items.menuItem')
            ->where('status', 'preparing')
            ->orderBy('created_at', 'asc')
            ->get();
    }

    public function startPreparing($orderId)
    {
        Order::where('id', $orderId)->update(['status' => 'preparing']);
    }

    public function markAsReady($orderId)
    {
        Order::where('id', $orderId)->update(['status' => 'ready']);
    }

    #[On('echo:kds-orders,.order.placed')]
    public function handleNewOrder($event)
    {
        // Get the order ID from the broadcasted event (fallback to 'New' if null)
        $orderId = $event['order']['id'] ?? 'New';
        
        // Dispatch the browser event and pass the Order ID
        $this->dispatch('play-kds-sound', orderId: $orderId);
    }
}
