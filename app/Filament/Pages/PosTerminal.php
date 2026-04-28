<?php

namespace App\Filament\Pages;

use App\Events\OrderPlaced;
use App\Models\CafeTable;
use App\Models\MenuCategory;
use App\Models\MenuItem;
use App\Models\Order;
use BackedEnum;
use Filament\Actions\Action;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Filament\Support\Enums\Width;
use Filament\Support\Icons\Heroicon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Livewire\Attributes\Computed;
use Livewire\Attributes\On;
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

    public bool $showPaymentModal = false;
    public ?string $qrisData = null;
    public ?int $pendingOrderId = null;

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

    protected function getHeaderActions(): array
    {
        return [
            Action::make('history')
                ->label('Order History')
                ->icon('heroicon-o-clock')
                ->color('gray')
                ->slideOver()
                ->modalHeading('Recent Orders')
                ->modalContent(function () {
                    $orders = Order::with(['cafeTable', 'items.menuItem'])
                        ->latest()
                        ->take(50)
                        ->get();
                        
                    return view('filament.pages.pos-history', ['orders' => $orders]);
                })
                ->modalSubmitAction(false),
        ];
    }

    public function addToCart(int $menuItemId)
    {
        $menuItem = MenuItem::find($menuItemId);
        if (! $menuItem || ! $menuItem->is_available) return;

        $price = $menuItem->has_discount ? $menuItem->discount_price : $menuItem->price;

        $this->cart[] = [
            'menu_item_id' => $menuItem->id,
            'name' => $menuItem->name,
            'price' => $price,
            'quantity' => 1,
            'note' => '',
        ];
    }

    public function removeFromCart(int $index)
    {
        unset($this->cart[$index]);
        $this->cart = array_values($this->cart);
    }

    public function generateQris()
    {
        $this->validate([
            'cart' => 'required|array|min:1',
            'customerName' => 'nullable|string|max:255',
            'selectedTable' => 'nullable|exists:cafe_tables,id',
        ]);

        // 1. Save the order as "AWAITING PAYMENT"
        $order = DB::transaction(function (): Order {
            $order = Order::create([
                'customer_name' => $this->customerName ?: 'Guest',
                'cafe_table_id' => $this->selectedTable,
                'total_amount' => $this->total,
                'status' => 'awaiting_payment', // 👈 Wait for Midtrans to confirm
            ]);

            foreach ($this->cart as $item) {
                $order->items()->create([
                    'menu_item_id' => $item['menu_item_id'],
                    'quantity' => $item['quantity'],
                    'price' => $item['price'],
                    'note' => $item['note'] ?? null,
                    'status' => 'pending' // 👈 Kitchen won't see it until paid
                ]);
            }

            return $order;
        });

        $this->pendingOrderId = $order->id;

        // 2. Call Midtrans API
        try {
            $serverKey = env('MIDTRANS_SERVER_KEY');
            $isProduction = env('MIDTRANS_IS_PRODUCTION', false);
            $baseUrl = $isProduction ? 'https://api.midtrans.com/v2' : 'https://api.sandbox.midtrans.com/v2';

            $itemDetails = collect($this->cart)->map(function ($item) {
                return [
                    'id' => (string) $item['menu_item_id'],
                    'price' => (int) $item['price'],
                    'quantity' => (int) $item['quantity'],
                    'name' => substr($item['name'], 0, 50), 
                ];
            })->toArray();

            $response = Http::withBasicAuth($serverKey, '')
                ->post($baseUrl . '/charge', [
                    'payment_type' => 'qris',
                    'transaction_details' => [
                        'order_id' => $order->id . '-' . time(), 
                        'gross_amount' => (int) $this->total,
                    ],
                    'item_details' => $itemDetails,
                    'customer_details' => [
                        'first_name' => $this->customerName ?: 'Guest',
                    ],
                    'qris' => [
                        'acquirer' => 'gopay'
                    ]
                ]);

            if ($response->successful()) {
                $responseData = $response->json();
                
                $actions = collect($responseData['actions'] ?? []);
                $qrAction = $actions->firstWhere('name', 'generate-qr-code');

                if ($qrAction && isset($qrAction['url'])) {
                    $this->qrisData = $qrAction['url']; 
                    $this->showPaymentModal = true;
                } else {
                    Notification::make()->title('QRIS URL not found in Midtrans response.')->danger()->send();
                }
            } else {
                Notification::make()
                    ->title('Midtrans Error: ' . $response->json('status_message', 'Unknown Error'))
                    ->danger()
                    ->send();
            }

        } catch (\Exception $e) {
            Notification::make()->title('Server Connection Error')->danger()->send();
        }
    }

    #[On('echo:kds-orders,.order.placed')]
    public function handleAutomatedPaymentSuccess($event)
    {
        $paidOrderId = $event['order']['id'] ?? null;

        if ($this->pendingOrderId && $this->pendingOrderId === $paidOrderId) {
            $this->reset(['cart', 'customerName', 'selectedTable', 'showPaymentModal', 'qrisData', 'pendingOrderId']);
            
            Notification::make()
                ->title('Payment Received! Sent to Kitchen.')
                ->success()
                ->send();
        }
    }

    public function confirmPaymentSuccess()
    {
        /** @var \App\Models\Order $order */
        $order = Order::find($this->pendingOrderId);

        if ($order && $order->status === 'awaiting_payment') {
            $order->update(['status' => 'pending']);
            broadcast(new OrderPlaced($order));
            
            $this->reset(['cart', 'customerName', 'selectedTable', 'showPaymentModal', 'qrisData', 'pendingOrderId']);
            Notification::make()->title('Manual Override: Sent to Kitchen.')->success()->send();
        }
    }

    public function cancelPayment()
    {
        if ($this->pendingOrderId) {
            Order::destroy($this->pendingOrderId);
        }
        $this->reset(['showPaymentModal', 'qrisData', 'pendingOrderId']);
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
                    'note' => $item['note'] ?? null,
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
