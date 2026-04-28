<x-filament-panels::page>
    <style>
        /* Structural Layout */
        .pos-wrapper { display: grid; grid-template-columns: 1fr; gap: 1.5rem; }
        @media (min-width: 768px) { .pos-wrapper { grid-template-columns: 2fr 1fr; } }
        
        /* Category Styling */
        .category-section { margin-bottom: 2rem; }
        .category-title { font-size: 1.25rem; font-weight: bold; margin-bottom: 1rem; border-bottom: 2px solid rgba(156, 163, 175, 0.2); padding-bottom: 0.5rem; }
        .pos-menu-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(160px, 1fr)); gap: 1rem; }
        
        /* Card Styling */
        .menu-card { display: flex; flex-direction: column; overflow: hidden; border-radius: 0.75rem; border: 1px solid rgba(156, 163, 175, 0.3); background: #fff; cursor: pointer; transition: transform 0.1s, box-shadow 0.1s; padding: 0; text-align: left; }
        .menu-card:hover { transform: translateY(-2px); box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1); border-color: var(--primary-500); }
        .dark .menu-card { background: #1f2937; border-color: #374151; }
        .dark .menu-card:hover { border-color: var(--primary-500); }
        
        /* Image & Content */
        .menu-card-img-wrapper { width: 100%; height: 120px; background-color: #f3f4f6; display: flex; align-items: center; justify-content: center; overflow: hidden; }
        .dark .menu-card-img-wrapper { background-color: #374151; }
        .menu-card-img { width: 100%; height: 100%; object-fit: cover; }
        .menu-card-content { padding: 0.75rem; display: flex; flex-direction: column; flex-grow: 1; justify-content: space-between; }
        .menu-card-title { font-weight: 600; line-height: 1.2; margin-bottom: 0.5rem; font-size: 0.95rem; }
        
        /* Cart Styling */
        .pos-cart-container { display: flex; flex-direction: column; height: 75vh; position: sticky; top: 2rem;}
        .pos-cart-items { flex: 1; overflow-y: auto; border-top: 1px solid rgba(156, 163, 175, 0.2); border-bottom: 1px solid rgba(156, 163, 175, 0.2); margin-bottom: 1rem; padding: 0.5rem 0; }

        .pos-select { color: #111827; }
        .pos-select option { background-color: #ffffff; color: #111827; }
        
        .dark .pos-select { color: #f9fafb; }
        .dark .pos-select option { background-color: #1f2937; color: #f9fafb; }
    </style>

    <div class="pos-wrapper">
        
        <div class="bg-white dark:bg-gray-900 rounded-xl shadow-sm p-6 ring-1 ring-gray-950/5 dark:ring-white/10">
            @forelse($this->categories as $category)
                <div class="category-section">
                    <h2 class="category-title text-gray-800 dark:text-gray-200">
                        {{ $category->name ?? 'Uncategorized' }}
                    </h2>
                    
                    <div class="pos-menu-grid">
                        @foreach($category->items as $item)
                            <button wire:click="addToCart({{ $item->id }})" class="menu-card">
                                
                                <div class="menu-card-img-wrapper">
                                    @if($item->image_path)
                                        <img src="{{ Storage::url($item->image_path) }}" alt="{{ $item->name }}" class="menu-card-img">
                                    @else
                                        <span class="text-gray-400 dark:text-gray-500 text-sm">No Image</span>
                                    @endif
                                </div>

                                <div class="menu-card-content">
                                    <span class="menu-card-title text-gray-800 dark:text-gray-200">{{ $item->name }}</span>
                                    <span class="font-bold" style="color: var(--primary-500); font-weight: bold;">
                                        Rp {{ number_format($item->has_discount ? $item->discount_price : $item->price, 2) }}
                                    </span>
                                </div>
                                
                            </button>
                        @endforeach
                    </div>
                </div>
            @empty
                <div class="p-8 text-center text-gray-500">
                    No active menu items or categories found.
                </div>
            @endforelse
        </div>

        <div class="bg-white dark:bg-gray-900 rounded-xl shadow-sm p-6 ring-1 ring-gray-950/5 dark:ring-white/10" 
             style="display: flex; flex-direction: column; height: 75vh; position: sticky; top: 2rem;">
            
            <h2 class="text-xl font-bold mb-4 pb-3" style="border-bottom: 1px solid rgba(156, 163, 175, 0.2);">
                Current Order
            </h2>
            
            <div style="margin-bottom: 1rem;">
                <input 
                    type="text" 
                    wire:model="customerName" 
                    placeholder="Customer Name (Optional)" 
                    style="width: 100%; padding: 0.75rem; border-radius: 0.5rem; border: 1px solid rgba(156, 163, 175, 0.3); background: transparent; outline: none;"
                >
            </div>

            <div style="flex: 1; overflow-y: auto; border-top: 1px solid rgba(156, 163, 175, 0.2); border-bottom: 1px solid rgba(156, 163, 175, 0.2); padding: 0.5rem 0; margin-bottom: 1rem;">
                @forelse($cart as $index => $item)
                    <div style="display: flex; flex-direction: column; padding: 0.75rem; border-bottom: 1px solid rgba(156, 163, 175, 0.2);">
                        
                        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 0.5rem;">
                            <div style="font-weight: bold; flex: 1;" class="dark:text-white">
                                {{ $item['name'] }}
                            </div>
                            
                            <div style="display: flex; align-items: center; gap: 0.75rem;">
                                <span style="font-size: 0.9rem; color: #6b7280;" class="dark:text-gray-400">
                                    Rp {{ number_format($item['price'], 0, ',', '.') }}
                                </span>
                                <button wire:click="removeFromCart({{ $index }})" style="color: #ef4444; background: transparent; border: none; cursor: pointer; padding: 0.25rem;">
                                    <svg style="width: 1.25rem; height: 1.25rem;" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                </button>
                            </div>
                        </div>

                        <input 
                            type="text" 
                            wire:model="cart.{{ $index }}.note" 
                            placeholder="Add note (e.g., Less ice, not spicy)..." 
                            style="width: 100%; padding: 0.4rem 0.5rem; font-size: 0.85rem; border-radius: 0.25rem; border: 1px dashed rgba(156, 163, 175, 0.5); background: transparent; outline: none;"
                            class="dark:text-white dark:border-gray-600 focus:border-blue-500"
                        >
                    </div>
                @empty
                    @endforelse
            </div>

            <div style="margin-top: auto; padding-top: 1rem;">
                <div style="display: flex; justify-content: space-between; align-items: center; font-size: 1.25rem; font-weight: bold; margin-bottom: 1.25rem;">
                    <span>Total:</span>
                    <span>Rp {{ number_format($this->total, 0, ',', '.') }}</span>
                </div>

                <select 
                    wire:model="selectedTable" 
                    class="pos-select"
                    style="width: 100%; padding: 0.75rem; border-radius: 0.5rem; border: 1px solid rgba(156, 163, 175, 0.3); background: transparent; outline: none; margin-bottom: 1rem;"
                >
                    <option value="">Select Table (Takeaway)</option>
                    @foreach($this->cafeTables as $table)
                        <option value="{{ $table->id }}">Table {{ $table->name }}</option>
                    @endforeach
                </select>
                
                <button 
                    wire:click="generateQris" 
                    @disabled(empty($cart))
                    style="width: 100%; padding: 1rem; background-color: #ea580c; color: white; border: none; border-radius: 0.5rem; font-weight: bold; font-size: 1.1rem; cursor: pointer; transition: opacity 0.2s;"
                    onmouseover="this.style.opacity='0.9'" 
                    onmouseout="this.style.opacity='1'"
                >
                    Pay with QRIS
                </button>
            </div>
        </div>

    </div>

    <div x-data="{ show: @entangle('showPaymentModal') }" 
         x-show="show" 
         style="display: none; position: fixed; inset: 0; z-index: 50; background-color: rgba(0, 0, 0, 0.75); backdrop-filter: blur(4px);"
         x-transition.opacity>
         
        <div style="display: flex; align-items: center; justify-content: center; width: 100%; height: 100%; padding: 1rem;">

            <div style="background-color: white; padding: 2rem; border-radius: 1rem; box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1); max-width: 28rem; width: 100%; text-align: center; position: relative;" class="dark:bg-gray-800">
                
                <h2 style="font-size: 1.5rem; font-weight: bold; margin-bottom: 0.5rem; color: #111827;" class="dark:text-white">Scan to Pay</h2>
                <p style="color: #6b7280; margin-bottom: 1.5rem;" class="dark:text-gray-400">Please scan this QRIS code using your e-wallet or mobile banking app.</p>

                <div style="background-color: white; padding: 1rem; border-radius: 0.75rem; display: inline-block; border: 2px solid #e5e7eb; margin-bottom: 2rem;">
                    @if($qrisData)
                        <img 
                            src="{{ $qrisData }}" 
                            alt="QRIS Code"
                            style="width: 250px; height: 250px; object-fit: contain;"
                        >
                    @endif
                </div>

                <div style="display: flex; flex-direction: column; gap: 0.75rem;">
                    <button wire:click="confirmPaymentSuccess" style="width: 100%; padding: 0.75rem; background-color: #22c55e; color: white; font-weight: bold; border-radius: 0.5rem; border: none; cursor: pointer;">
                        [Sandbox] Simulate Payment Success
                    </button>
                    
                    <button wire:click="cancelPayment" style="width: 100%; padding: 0.75rem; background-color: #ef4444; color: white; font-weight: bold; border-radius: 0.5rem; border: none; cursor: pointer;">
                        Cancel Order
                    </button>
                </div>
            </div>
            
        </div>
    </div>
</x-filament-panels::page>