<div class="min-h-screen bg-[#f4ebd8] pb-24 font-sans text-[#2b1010]" 
     x-data="{ showToast: false, successToast: false }" 
     @item-added.window="showToast = true; setTimeout(() => showToast = false, 2000)"
     @payment-success.window="successToast = true; setTimeout(() => successToast = false, 5000)">
    
<header class="sticky top-0 z-20 bg-[#6b1d1d] shadow-md border-b-4 border-[#b89047]">
        <div class="max-w-4xl mx-auto px-4 py-4 flex items-center gap-4">
            <div class="flex-shrink-0 bg-[#f4ebd8] p-1.5 rounded-lg border-2 border-[#b89047] shadow-inner">
                <img src="{{ asset('storage/logo/icon.png') }}" alt="Cafe Logo" class="h-18 w-auto object-contain group-hover:opacity-90 transition">
            </div>

            <div class="flex-1 min-w-0">
                <div class="flex items-center gap-1.5">
                    <h1 class="text-lg font-bold font-serif text-[#f4ebd8] tracking-tight truncate">
                        Natural Twenty Board Game Cafe
                    </h1>
                </div>

                <div class="flex items-start gap-1 mt-0.5 text-[#e3c78a]">
                    <svg class="w-3 h-3 mt-0.5 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                    </svg>
                    <span class="text-[10px] font-medium leading-tight opacity-90">Ruko Ruby Commercial Estate, Jl. Bulevar Selatan Blok TD 09</span>
                </div>

                <div class="flex items-center justify-between mt-2 pt-1.5 border-t border-[#b89047]/30">
                    <div class="flex items-center gap-1 text-[#e3c78a]">
                        <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        <span class="text-[10px] font-bold uppercase tracking-wider">10:00 – 22:00</span>
                    </div>
                    <div class="bg-[#b89047] px-2 py-0.5 rounded text-[9px] font-black text-[#f4ebd8] uppercase tracking-tighter">
                        Table {{ $table->name }}
                    </div>
                </div>
            </div>
        </div>
    </header>

    <main class="p-4 max-w-4xl mx-auto">
        @foreach($this->categories as $category)
            <div class="mb-10">
                <h2 class="mb-4 text-2xl font-bold font-serif text-[#4a1515] border-b-2 border-[#b89047]/40 pb-2 uppercase tracking-wide">
                    {{ $category->name }}
                </h2>
                
                <div class="grid grid-cols-1 gap-3">
                    @foreach($category->items as $item)
                        <div class="flex overflow-hidden rounded-xl bg-[#fffcf5] shadow-sm border border-[#decfa8] transition duration-200 active:scale-[0.98]">
                            
                            <div class="w-32 h-32 flex-shrink-0 border-r border-[#decfa8]">
                                @if($item->image_path)
                                    <img src="{{ Storage::url($item->image_path) }}" class="w-full h-full object-cover">
                                @else
                                    <div class="flex h-full w-full items-center justify-center bg-[#eae0c8] text-[#8c7b5a] text-[10px] font-serif italic text-center px-2">No Image</div>
                                @endif
                            </div>

                            <div class="flex flex-1 flex-col p-3 relative">
                                <h3 class="text-base font-bold font-serif text-[#2b1010] leading-tight">{{ $item->name }}</h3>
                                
                                <p class="mt-1 text-xs text-[#735e3b] line-clamp-2 leading-relaxed">
                                    {{ $item->description ?? 'Traditional recipe prepared with the finest tavern ingredients.' }}
                                </p>
                                
                                <div class="mt-auto flex items-center justify-between">
                                    <p class="text-sm font-bold text-[#8b2626]">Rp {{ number_format($item->price, 0, ',', '.') }}</p>
                                    
                                    <button wire:click="addToCart({{ $item->id }})" 
                                            class="flex h-8 w-8 items-center justify-center rounded-lg bg-[#6b1d1d] text-[#f4ebd8] shadow-sm transition hover:bg-[#4a1515] active:bg-[#b89047]">
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="3" stroke="currentColor" class="w-5 h-5">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                                        </svg>
                                    </button>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        @endforeach
    </main>

    @if(count($cart) > 0 && !$showCartModal && !$showPaymentModal)
        <div class="fixed bottom-0 left-0 right-0 z-20 border-t-4 border-[#b89047] bg-[#6b1d1d] p-4 shadow-[0_-10px_20px_rgba(0,0,0,0.15)]">
            <button wire:click="$set('showCartModal', true)" class="flex w-full items-center justify-between rounded bg-[#b89047] px-5 py-3.5 font-bold font-serif text-[#2b1010] shadow-lg transition hover:bg-[#cca356] max-w-4xl mx-auto">
                <span class="flex items-center gap-3">
                    <span class="flex h-7 w-7 items-center justify-center rounded-full bg-[#2b1010] text-sm text-[#b89047]">{{ count($cart) }}</span>
                    View Order
                </span>
                <span class="text-lg">Rp {{ number_format($this->total, 0, ',', '.') }}</span>
            </button>
        </div>
    @endif

    @if($showCartModal)
        <div class="fixed inset-0 z-50 flex flex-col bg-[#f4ebd8]">
            <header class="flex items-center justify-between border-b-4 border-[#b89047] bg-[#6b1d1d] p-4 text-[#f4ebd8]">
                <h2 class="text-xl font-bold font-serif">Table {{ $table->name }}</h2>
                <button wire:click="$set('showCartModal', false)" class="text-[#e3c78a] text-3xl font-bold hover:text-white transition">&times;</button>
            </header>

            <div class="flex-1 overflow-y-auto p-4 max-w-4xl mx-auto w-full">
                <label class="block font-serif font-bold text-[#4a1515] mb-1">Adventurer's Name</label>
                <input type="text" wire:model="customerName" placeholder="What should we call you?" class="mb-6 w-full rounded bg-[#fffcf5] border border-[#decfa8] p-3 outline-none focus:border-[#b89047] focus:ring-1 focus:ring-[#b89047] text-[#2b1010] placeholder-[#a8997a]">
                @error('customerName') <span class="text-sm font-bold text-[#b91c1c]">{{ $message }}</span> @enderror

                <div class="flex flex-col gap-4 border-t-2 border-[#decfa8] pt-6">
                    @foreach($cart as $index => $item)
                        <div class="flex flex-col gap-3 rounded bg-[#fffcf5] p-4 shadow-sm border border-[#decfa8]">
                            <div class="flex justify-between font-bold text-[#2b1010] font-serif text-lg">
                                <span>{{ $item['name'] }}</span>
                                <span class="text-[#8b2626]">Rp {{ number_format($item['price'], 0, ',', '.') }}</span>
                            </div>
                            <div class="flex items-center gap-3">
                                <input type="text" wire:model="cart.{{ $index }}.note" placeholder="Add special request..." class="flex-1 rounded border border-[#decfa8] bg-white px-3 py-1.5 text-sm outline-none focus:border-[#b89047]">
                                <button wire:click="removeFromCart({{ $index }})" class="rounded border border-[#b91c1c] bg-[#fef2f2] px-3 py-1.5 text-xs font-bold text-[#b91c1c] hover:bg-[#fee2e2] transition">Remove</button>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            <div class="border-t border-[#decfa8] bg-[#eaddbc] p-4">
                <button wire:click="generateQris" class="w-full max-w-4xl mx-auto block rounded bg-[#6b1d1d] py-3.5 font-bold font-serif text-[#f4ebd8] text-xl border border-[#4a1515] shadow-md hover:bg-[#4a1515] transition">
                    Pay Now (Rp {{ number_format($this->total, 0, ',', '.') }})
                </button>
            </div>
        </div>
    @endif

    @if($showPaymentModal)
    <div class="fixed inset-0 z-50 flex items-center justify-center bg-[#2b1010]/90 backdrop-blur-sm p-4">
        <div class="w-full max-w-sm rounded bg-[#f4ebd8] p-8 text-center shadow-2xl border-2 border-[#b89047]">
            <h2 class="mb-2 text-3xl font-black font-serif text-[#4a1515]">Scan to Pay</h2>
            <p class="mb-6 text-sm text-[#735e3b] font-medium leading-relaxed">Use GoPay, OVO, or your Mobile Banking app to settle your tab.</p>
            
            <div class="mx-auto mb-6 inline-block overflow-hidden rounded bg-white p-3 border-4 border-[#b89047] shadow-inner">
                <img src="{{ $qrisData }}" class="h-64 w-64 object-contain" id="qris-image">
            </div>

            <a href="{{ $qrisData }}" 
               download="QRIS-Table-{{ $table->name }}-{{ date('His') }}.png"
               target="_blank"
               class="mb-3 flex items-center justify-center gap-2 w-full rounded border-2 border-[#b89047] bg-[#eaddbc] py-3 font-bold font-serif text-[#4a1515] shadow-sm active:bg-[#decfa8] transition hover:bg-[#decfa8]">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" class="w-5 h-5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75V16.5M7.5 12L12 16.5m0 0l4.5-4.5M12 16.5V3" />
                </svg>
                Save QRIS Image
            </a>

            <button wire:click="handleAutomatedPaymentSuccess(['order' => ['id' => {{ $this->pendingOrderId }} ]])" 
                    class="mb-3 w-full rounded border border-[#b89047] bg-[#6b1d1d] py-2 font-bold font-serif text-[#f4ebd8] opacity-80 hover:opacity-100">
                [DEV] Force Payment Success
            </button>

            <button wire:click="$set('showPaymentModal', false)" 
                    class="w-full rounded border border-[#decfa8] bg-[#eaddbc] py-3 font-bold text-[#4a1515] hover:bg-[#decfa8] transition">
                Cancel / Close
            </button>
        </div>
    </div>
@endif

    <div x-show="showToast" style="display: none;" class="fixed top-24 left-1/2 z-50 -translate-x-1/2 rounded border border-[#b89047] bg-[#6b1d1d] px-6 py-3 text-sm font-bold font-serif text-[#f4ebd8] shadow-xl">
        Item added to your tab!
    </div>
    
    <div x-show="successToast" style="display: none;" class="fixed inset-0 z-50 flex items-center justify-center bg-[#2b1010]/90 p-4 text-center backdrop-blur-sm">
        <div class="rounded bg-[#f4ebd8] p-10 shadow-2xl border-4 border-[#b89047]">
            <div class="mx-auto mb-5 flex h-20 w-20 items-center justify-center rounded-full bg-[#6b1d1d] text-4xl border-2 border-[#b89047] text-[#b89047]">🐉</div>
            <h2 class="text-3xl font-black font-serif text-[#4a1515]">Huzzah!</h2>
            <p class="mt-3 text-lg font-medium text-[#735e3b]">Your payment is complete.<br>The kitchen is preparing your feast.</p>
        </div>
    </div>

    <footer class="mt-12 mb-8 px-4 text-center">
        <div class="max-w-4xl mx-auto border-t border-[#decfa8] pt-8">
            <p class="text-[10px] font-bold tracking-[0.2em] text-[#735e3b] uppercase leading-relaxed">
                &copy; {{ date('Y') }} PT AETHERWYN FANTASIA INDONESIA.<br>
                All Rights Reserved.
            </p>
        </div>
    </footer>

</div>