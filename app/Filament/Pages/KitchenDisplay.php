<?php

namespace App\Filament\Pages;

use App\Models\Order;
use App\Models\OrderItem;
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

    public string $activeStation = 'kitchen';

    #[Computed]
    public function pendingOrders()
    {
        return Order::with(['cafeTable', 'items' => function ($query) {
                
                $query->where('status', 'pending')
                      ->whereHas('menuItem', function ($q) {
                          $q->where('station', $this->activeStation);
                      })->with('menuItem');
            }])
            ->whereHas('items', function ($query) {
                $query->where('status', 'pending')
                      ->whereHas('menuItem', function ($q) {
                          $q->where('station', $this->activeStation);
                      });
            })
            ->orderBy('created_at', 'asc')
            ->get();
    }

    #[Computed]
    public function preparingOrders()
    {
        return Order::with(['cafeTable', 'items' => function ($query) {
                $query->where('status', 'preparing')
                      ->whereHas('menuItem', function ($q) {
                          $q->where('station', $this->activeStation);
                      })->with('menuItem');
            }])
            ->whereHas('items', function ($query) {
                $query->where('status', 'preparing')
                      ->whereHas('menuItem', function ($q) {
                          $q->where('station', $this->activeStation);
                      });
            })
            ->orderBy('created_at', 'asc')
            ->get();
    }

    public function startPreparing($orderId)
    {
        OrderItem::where('order_id', $orderId)
            ->whereHas('menuItem', function($q) {
                $q->where('station', $this->activeStation);
            })
            ->update(['status' => 'preparing']);
    }

    public function markAsReady($orderId)
    {
        OrderItem::where('order_id', $orderId)
            ->whereHas('menuItem', function($q) {
                $q->where('station', $this->activeStation);
            })
            ->update(['status' => 'ready']);
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
