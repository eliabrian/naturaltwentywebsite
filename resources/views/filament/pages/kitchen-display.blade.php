<x-filament-panels::page>
    <style>
        /* Safe Color-Only Classes for Dark Mode Support */
        .kds-column { background-color: #f3f4f6; border: 1px solid transparent; }
        .dark .kds-column { background-color: #1f2937; border-color: #374151; }

        .kds-card { background-color: #ffffff; }
        .dark .kds-card { background-color: #111827; }

        .kds-card-header { background-color: #f9fafb; border-bottom-color: #e5e7eb; }
        .dark .kds-card-header { background-color: #1f2937; border-bottom-color: #374151; }

        .kds-text-main { color: #111827; }
        .dark .kds-text-main { color: #f9fafb; }

        .kds-text-muted { color: #6b7280; }
        .dark .kds-text-muted { color: #9ca3af; }
        
        .kds-qty-badge { background-color: #e5e7eb; color: #111827; }
        .dark .kds-qty-badge { background-color: #374151; color: #f9fafb; }
        
        .kds-divider { border-bottom-color: rgba(156, 163, 175, 0.2); }
        .dark .kds-divider { border-bottom-color: rgba(255, 255, 255, 0.1); }
    </style>

    <div 
        x-data="{ 
            permission: Notification.permission,
            
            requestPermission() {
                Notification.requestPermission().then(status => {
                    this.permission = status;
                });
            },

            notifyAndPlay(orderId) { 
                // 1. Try to play the custom MP3 sound
                let audio = $refs.notificationSound;
                audio.currentTime = 0; 
                audio.play().catch(e => console.warn('Custom audio blocked. Relying on native notification.')); 

                // 2. Trigger the Native Tablet Notification!
                if (this.permission === 'granted') {
                    new Notification('👨‍🍳 New Order Arrived!', {
                        body: 'Order #' + orderId + ' has been sent to the kitchen.',
                        // Optional: Add the path to your cafe's logo if you have one in the public folder
                        // icon: '/images/cafe-logo.png' 
                    });
                }
            } 
        }"
        @play-kds-sound.window="notifyAndPlay($event.detail.orderId)"
    >
        <audio x-ref="notificationSound" src="{{ asset('sounds/videoplayback.weba') }}" preload="auto"></audio>

        <template x-if="permission !== 'granted' && permission !== 'denied'">
            <div style="background-color: #fef3c7; border: 1px solid #f59e0b; color: #b45309; padding: 1rem; border-radius: 0.5rem; margin-bottom: 1.5rem; display: flex; justify-content: space-between; align-items: center;">
                <span style="font-weight: bold;">⚠️ Please enable notifications to hear alerts for new orders.</span>
                <button 
                    @click="requestPermission()" 
                    style="background-color: #f59e0b; color: white; padding: 0.5rem 1rem; border-radius: 0.375rem; font-weight: bold; cursor: pointer; border: none;"
                >
                    Enable Notifications
                </button>
            </div>
        </template>
        
        <template x-if="permission === 'denied'">
            <div style="background-color: #fee2e2; border: 1px solid #ef4444; color: #b91c1c; padding: 1rem; border-radius: 0.5rem; margin-bottom: 1.5rem;">
                Notifications are blocked by your browser. Please allow them in your site settings to receive order alerts.
            </div>
        </template>
    </div>

    <div style="display: flex; gap: 1rem; margin-bottom: 2rem; background: rgba(0,0,0,0.02); padding: 0.5rem; border-radius: 0.75rem; border: 1px solid rgba(156, 163, 175, 0.2);" class="dark:bg-gray-900/50 dark:border-gray-800">
        <button 
            wire:click="$set('activeStation', 'kitchen')"
            style="flex: 1; padding: 1rem; border-radius: 0.5rem; font-weight: bold; font-size: 1.25rem; transition: all 0.2s; border: none; cursor: pointer; {{ $activeStation === 'kitchen' ? 'background-color: #ef4444; color: white; box-shadow: 0 4px 6px -1px rgba(0,0,0,0.1);' : 'background: transparent; color: #6b7280;' }}"
        >
            👨‍🍳 Kitchen View
        </button>
        
        <button 
            wire:click="$set('activeStation', 'bar')"
            style="flex: 1; padding: 1rem; border-radius: 0.5rem; font-weight: bold; font-size: 1.25rem; transition: all 0.2s; border: none; cursor: pointer; {{ $activeStation === 'bar' ? 'background-color: #3b82f6; color: white; box-shadow: 0 4px 6px -1px rgba(0,0,0,0.1);' : 'background: transparent; color: #6b7280;' }}"
        >
            🍹 Bar View
        </button>
    </div>

    <div style="display: grid; grid-template-columns: 1fr; gap: 1.5rem; align-items: start; min-height: 80vh;" class="lg:grid-cols-2">
        
        <div class="kds-column" style="border-radius: 1rem; padding: 1.5rem;">
            <h2 style="font-size: 1.5rem; font-weight: bold; margin-bottom: 1.5rem; border-bottom: 4px solid #ef4444; padding-bottom: 0.5rem; color: #ef4444;">
                🔥 New Orders ({{ count($this->pendingOrders) }})
            </h2>
            
            <div style="display: flex; flex-direction: column; gap: 1rem;">
                @forelse($this->pendingOrders as $order)
                    <div class="kds-card" style="border-radius: 0.75rem; border-left: 6px solid #ef4444; box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1); overflow: hidden;">
                        
                        <div class="kds-card-header" style="display: flex; justify-content: space-between; align-items: center; padding: 1rem; border-bottom-width: 1px; border-bottom-style: solid;">
                            <div style="display: flex; gap: 1rem; align-items: center;">
                                <div style="display: flex; flex-direction: column;">
                                    <span class="kds-text-main" style="font-weight: bold; font-size: 1.1rem;">Order #{{ $order->id }}</span>
                                    <span class="kds-text-muted" style="font-size: 0.85rem;">{{ $order->customer_name }}</span>
                                </div>
                                
                                @if($order->cafeTable)
                                    <span style="background: #3b82f6; color: white; padding: 0.25rem 0.75rem; border-radius: 0.5rem; font-weight: bold; font-size: 0.9rem;">
                                        Table {{ $order->cafeTable->name }}
                                    </span>
                                @else
                                    <span style="background: #8b5cf6; color: white; padding: 0.25rem 0.75rem; border-radius: 0.5rem; font-weight: bold; font-size: 0.9rem;">
                                        Takeaway
                                    </span>
                                @endif
                            </div>
                            <span style="font-size: 0.85rem; font-weight: bold; color: #ef4444;">{{ $order->created_at->diffForHumans() }}</span>
                        </div>

                        <div style="padding: 1rem; display: flex; flex-direction: column; gap: 0.75rem;">
                            @foreach($order->items as $item)
                                <div class="kds-divider" style="display: flex; justify-content: space-between; align-items: flex-start; border-bottom-width: 1px; border-bottom-style: dashed; padding-bottom: 0.5rem;">
                                    <div>
                                        <span class="kds-text-main" style="font-weight: bold; font-size: 1.1rem;">{{ $item->menuItem->name }}</span>
                                        @if($item->special_instructions)
                                            <p style="font-size: 0.85rem; color: #ea580c; font-style: italic; margin-top: 0.25rem;">Note: {{ $item->special_instructions }}</p>
                                        @endif
                                    </div>
                                    <span class="kds-qty-badge" style="padding: 0.25rem 0.75rem; border-radius: 9999px; font-weight: 900; font-size: 1.2rem;">
                                        x{{ $item->quantity }}
                                    </span>
                                </div>
                            @endforeach
                        </div>

                        <div style="padding: 1rem; padding-top: 0;">
                            <button 
                                wire:click="startPreparing({{ $order->id }})"
                                style="width: 100%; padding: 0.75rem; background-color: #ef4444; color: white; border: none; border-radius: 0.5rem; font-weight: bold; font-size: 1rem; cursor: pointer; transition: opacity 0.2s;"
                                onmouseover="this.style.opacity='0.9'" 
                                onmouseout="this.style.opacity='1'"
                            >
                                Start Preparing &rarr;
                            </button>
                        </div>
                    </div>
                @empty
                    <div class="kds-text-muted" style="text-align: center; padding: 2rem; font-size: 1.1rem; font-weight: bold;">
                        No pending orders.
                    </div>
                @endforelse
            </div>
        </div>

        <div class="kds-column" style="border-radius: 1rem; padding: 1.5rem;">
            <h2 style="font-size: 1.5rem; font-weight: bold; margin-bottom: 1.5rem; border-bottom: 4px solid #ea580c; padding-bottom: 0.5rem; color: #ea580c;">
                👨‍🍳 Cooking ({{ count($this->preparingOrders) }})
            </h2>
            
            <div style="display: flex; flex-direction: column; gap: 1rem;">
                @forelse($this->preparingOrders as $order)
                    <div class="kds-card" style="border-radius: 0.75rem; border-left: 6px solid #ea580c; box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1); overflow: hidden;">
                        
                        <div class="kds-card-header" style="display: flex; justify-content: space-between; align-items: center; padding: 1rem; border-bottom-width: 1px; border-bottom-style: solid;">
                            <div style="display: flex; flex-direction: column;">
                                <span class="kds-text-main" style="font-weight: bold; font-size: 1.1rem;">Order #{{ $order->id }}</span>
                                <span class="kds-text-muted" style="font-size: 0.85rem;">{{ $order->customer_name }}</span>
                            </div>
                        </div>

                        <div style="padding: 1rem; display: flex; flex-direction: column; gap: 0.75rem;">
                            @foreach($order->items as $item)
                                <div class="kds-divider" style="display: flex; justify-content: space-between; align-items: flex-start; border-bottom-width: 1px; border-bottom-style: dashed; padding-bottom: 0.5rem;">
                                    <div>
                                        <span class="kds-text-main" style="font-weight: bold; font-size: 1.1rem;">{{ $item->menuItem->name }}</span>
                                    </div>
                                    <span class="kds-qty-badge" style="padding: 0.25rem 0.75rem; border-radius: 9999px; font-weight: 900; font-size: 1.2rem;">
                                        x{{ $item->quantity }}
                                    </span>
                                </div>
                            @endforeach
                        </div>

                        <div style="padding: 1rem; padding-top: 0;">
                            <button 
                                wire:click="markAsReady({{ $order->id }})"
                                style="width: 100%; padding: 0.75rem; background-color: #22c55e; color: white; border: none; border-radius: 0.5rem; font-weight: bold; font-size: 1rem; cursor: pointer; transition: opacity 0.2s;"
                                onmouseover="this.style.opacity='0.9'" 
                                onmouseout="this.style.opacity='1'"
                            >
                                ✔️ Mark as Ready
                            </button>
                        </div>
                    </div>
                @empty
                    <div class="kds-text-muted" style="text-align: center; padding: 2rem; font-size: 1.1rem; font-weight: bold;">
                        No orders currently cooking.
                    </div>
                @endforelse
            </div>
        </div>

    </div>
</x-filament-panels::page>