<?php

namespace App\Filament\Pages;

use App\Events\OrderPlaced;
use App\Models\CafeTable;
use App\Models\MenuCategory;
use App\Models\MenuItem;
use App\Models\Order;
use BackedEnum;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Filament\Support\Enums\Width;
use Filament\Support\Icons\Heroicon;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\Computed;
use UnitEnum;

class PosTerminal extends Page
{
    protected string $view = 'filament.pages.pos-terminal';

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedComputerDesktop;

    protected static ?string $navigationLabel = 'POS Terminal';

    protected static ?string $title = "Point of Sales";

    protected static string|UnitEnum|null $navigationGroup = "Store Operations";

    public array $cart = [];
    public string $customerName = '';
    public ?int $selectedTable = null;

    #[Computed]
    public function categories()
    {
        return MenuCategory::with(['items' => function ($query) {
            $query->where('is_available', true);
        }])
        ->whereHas('items', function ($query) {
            $query->where('is_available', true); 
        })
        ->where('is_active', true) 
        ->orderBy('sort_order', 'asc')
        ->get();
    }

    #[Computed]
    public function total()
    {
        return collect($this->cart)->sum(fn ($item) => $item['price'] * $item['quantity']);
    }

    #[Computed]
    public function cafeTables()
    {
        return CafeTable::orderBy('name')->get();
    }

    public function addToCart(int $menuItemId)
    {
        $menuItem = MenuItem::find($menuItemId);
        if (! $menuItem || ! $menuItem->is_available) return;

        $existingKey = collect($this->cart)->search(fn ($item) => $item['menu_item_id'] === $menuItem->id);

        if ($existingKey !== false) {
            $this->cart[$existingKey]['quantity']++;
        } else {
            $price = $menuItem->has_discount ? $menuItem->discount_price : $menuItem->price;

            $this->cart[] = [
                'menu_item_id' => $menuItem->id,
                'name' => $menuItem->name,
                'price' => $price,
                'quantity' => 1,
            ];
        }
    }

    public function removeFromCart(int $index)
    {
        unset($this->cart[$index]);
        $this->cart = array_values($this->cart);
    }

    public function checkout()
    {
        $this->validate([
            'cart' => 'required|array|min:1',
            'customerName' => 'nullable|string|max:255',
            'selectedTable' => 'nullable|exists:cafe_tables,id',
        ]);

        $order = DB::transaction(function () {
            $order = Order::create([
                'customer_name' => $this->customerName ?: 'Guest',
                'cafe_table_id' => $this->selectedTable,
                'total_amount' => $this->total,
                'status' => 'pending',
            ]);

            foreach ($this->cart as $item) {
                $order->items()->create([
                    'menu_item_id' => $item['menu_item_id'],
                    'quantity' => $item['quantity'],
                    'price' => $item['price'],
                ]);
            }

            return $order;
        });

        broadcast(new OrderPlaced($order));

        $this->reset(['cart', 'customerName', 'selectedTable']);
        
        Notification::make()
            ->title('Order Sent to Kitchen')
            ->success()
            ->send();
    }
}
