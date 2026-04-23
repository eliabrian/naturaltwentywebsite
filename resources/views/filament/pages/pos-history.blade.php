<div style="display: flex; flex-direction: column; gap: 1rem;">
    
    <style>
        .ph-card { background-color: rgba(0,0,0,0.02); border-color: rgba(156, 163, 175, 0.3); }
        .dark .ph-card { background-color: rgba(31, 41, 55, 0.5); border-color: rgba(55, 65, 81, 1); }

        .ph-text-main { color: #111827; }
        .dark .ph-text-main { color: #f9fafb; }

        .ph-text-muted { color: #6b7280; }
        .dark .ph-text-muted { color: #9ca3af; }
    </style>

    @forelse($orders as $order)
        <div class="ph-card" style="padding: 1rem; border-width: 1px; border-style: solid; border-radius: 0.5rem; display: flex; justify-content: space-between; align-items: flex-start;">
            
            <div style="display: flex; flex-direction: column; gap: 0.25rem; max-width: 70%;">
                <div style="display: flex; align-items: center; gap: 0.75rem;">
                    <span class="ph-text-main" style="font-weight: bold; font-size: 1.1rem;">Order #{{ $order->id }}</span>
                    <span class="ph-text-muted" style="font-size: 0.85rem;">{{ $order->created_at->format('d M Y, H:i') }}</span>
                </div>
                
                <span style="font-size: 0.95rem; color: #3b82f6; font-weight: bold;">
                    {{ $order->cafeTable ? 'Table ' . $order->cafeTable->name : 'Takeaway' }}
                    @if($order->customer_name)
                        <span class="ph-text-muted" style="font-weight: normal;">&bull; {{ $order->customer_name }}</span>
                    @endif
                </span>

                <span class="ph-text-muted" style="font-size: 0.85rem; margin-top: 0.5rem; line-height: 1.4;">
                    {{ Str::limit($order->items->map(fn($item) => $item->quantity . 'x ' . $item->menuItem->name)->implode(', '), 60) }}
                </span>
            </div>

            <div style="display: flex; flex-direction: column; align-items: flex-end; gap: 0.5rem;">
                <span class="ph-text-main" style="font-weight: bold; font-size: 1.1rem;">Rp {{ number_format($order->total_amount, 0, ',', '.') }}</span>
                
                @if($order->status === 'pending')
                    <span style="background: #fee2e2; color: #ef4444; padding: 0.25rem 0.5rem; border-radius: 0.25rem; font-size: 0.75rem; font-weight: bold; text-transform: uppercase;">Pending</span>
                @elseif($order->status === 'preparing')
                    <span style="background: #ffedd5; color: #ea580c; padding: 0.25rem 0.5rem; border-radius: 0.25rem; font-size: 0.75rem; font-weight: bold; text-transform: uppercase;">Cooking</span>
                @else
                    <span style="background: #dcfce3; color: #22c55e; padding: 0.25rem 0.5rem; border-radius: 0.25rem; font-size: 0.75rem; font-weight: bold; text-transform: uppercase;">Done</span>
                @endif
            </div>

        </div>
    @empty
        <div class="ph-text-muted" style="text-align: center; padding: 3rem; display: flex; flex-direction: column; gap: 1rem; align-items: center;">
            <svg style="width: 3rem; height: 3rem; opacity: 0.5;" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
            <span>No order history found.</span>
        </div>
    @endforelse
</div>