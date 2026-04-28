<?php

namespace App\Livewire\Customer;

use App\Models\CafeTable;
use App\Models\MenuCategory;
use App\Models\MenuItem;
use App\Models\Order;
use App\Events\OrderPlaced;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Livewire\Component;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Layout;
use Livewire\Attributes\On;

#[Layout('components.layouts.customer')]
class MenuBrowser extends Component
{
    public CafeTable $table;
    
    public array $cart = [];
    public string $customerName = '';
    public string $noteInput = '';
    
    public bool $showCartModal = false;
    public bool $showPaymentModal = false;
    
    public ?string $qrisData = null;
    public ?int $pendingOrderId = null;

    public function mount($token)
    {
        // 1. Find the table by the secure token. If invalid, throw a 404 error!
        $this->table = CafeTable::where('token', $token)->firstOrFail();
    }

    #[Computed]
    public function categories()
    {
        return MenuCategory::with(['items' => fn($q) => $q->where('is_available', true)])
            ->where('is_active', true)
            ->orderBy('sort_order')
            ->get();
    }

    #[Computed]
    public function total()
    {
        return collect($this->cart)->sum(fn ($item) => $item['price'] * $item['quantity']);
    }

    public function addToCart($menuItemId)
    {
        $menuItem = MenuItem::find($menuItemId);
        if (! $menuItem || ! $menuItem->is_available) return;

        $this->cart[] = [
            'menu_item_id' => $menuItem->id,
            'name' => $menuItem->name,
            'price' => $menuItem->has_discount ? $menuItem->discount_price : $menuItem->price,
            'quantity' => 1,
            'note' => '',
        ];
        
        $this->dispatch('item-added'); 
    }

    public function removeFromCart($index)
    {
        unset($this->cart[$index]);
        $this->cart = array_values($this->cart);
        if (empty($this->cart)) {
            $this->showCartModal = false;
        }
    }

    public function generateQris()
    {
        $this->validate([
            'cart' => 'required|array|min:1',
            'customerName' => 'required|string|max:50',
        ]);

        $order = DB::transaction(function (): Order {
            $order = Order::create([
                'customer_name' => $this->customerName,
                'cafe_table_id' => $this->table->id,
                'total_amount' => $this->total,
                'status' => 'awaiting_payment',
            ]);

            foreach ($this->cart as $item) {
                $order->items()->create([
                    'menu_item_id' => $item['menu_item_id'],
                    'quantity' => $item['quantity'],
                    'price' => $item['price'],
                    'note' => $item['note'] ?? null,
                    'status' => 'pending'
                ]);
            }
            return $order;
        });

        $this->pendingOrderId = $order->id;

        try {
            $serverKey = env('MIDTRANS_SERVER_KEY');
            $baseUrl = env('MIDTRANS_IS_PRODUCTION', false) ? 'https://api.midtrans.com/v2' : 'https://api.sandbox.midtrans.com/v2';

            $itemDetails = collect($this->cart)->map(fn($item) => [
                'id' => (string) $item['menu_item_id'],
                'price' => (int) $item['price'],
                'quantity' => (int) $item['quantity'],
                'name' => substr($item['name'], 0, 50),
            ])->toArray();

            $response = Http::withBasicAuth($serverKey, '')
                ->post($baseUrl . '/charge', [
                    'payment_type' => 'qris',
                    'transaction_details' => [
                        'order_id' => $order->id . '-' . time(),
                        'gross_amount' => (int) $this->total,
                    ],
                    'item_details' => $itemDetails,
                    'customer_details' => ['first_name' => $this->customerName],
                    'qris' => ['acquirer' => 'gopay']
                ]);

            if ($response->successful() && $qrAction = collect($response->json('actions'))->firstWhere('name', 'generate-qr-code')) {
                $this->qrisData = $qrAction['url'];
                $this->showCartModal = false;
                $this->showPaymentModal = true;
            }
        } catch (\Exception $e) {
            // Handle error
        }
    }

    #[On('echo:kds-orders,.order.placed')]
    public function handleAutomatedPaymentSuccess($event = [])
    {
        $orderId = $event['order']['id'] ?? null;
        $order = Order::find($orderId);

        if ($order && $order->status === 'awaiting_payment') {
            // 1. Update the database
            $order->update(['status' => 'pending']);

            event(new OrderPlaced($order)); 

            if ($this->pendingOrderId == $orderId) {
                $this->reset(['cart', 'customerName', 'showPaymentModal', 'qrisData', 'pendingOrderId']);
                $this->dispatch('payment-success'); 
            }
        }
    }
    
    public function render()
    {
        return view('livewire.customer.menu-browser');
    }
}