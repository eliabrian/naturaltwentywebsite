<style>
    /* Table Wrapper */
    .custom-sales-wrapper {
        border-radius: 0.75rem;
        background-color: #ffffff;
        box-shadow: 0 1px 2px 0 rgba(0, 0, 0, 0.05);
        border: 1px solid rgba(3, 7, 18, 0.05);
        overflow: hidden;
    }
    .dark .custom-sales-wrapper {
        background-color: #111827; 
        border-color: rgba(255, 255, 255, 0.1);
    }

    /* Table Basics */
    .custom-sales-table {
        width: 100%;
        border-collapse: collapse;
        font-size: 0.875rem;
        text-align: left;
    }
    
    /* Table Header */
    .custom-sales-thead { background-color: #f9fafb; }
    .dark .custom-sales-thead { background-color: rgba(255, 255, 255, 0.05); }
    
    .custom-sales-th {
        padding: 0.75rem 1rem;
        font-weight: 600;
        color: #030712;
        border-bottom: 1px solid #e5e7eb;
    }
    .dark .custom-sales-th {
        color: #ffffff;
        border-bottom-color: rgba(255, 255, 255, 0.05);
    }

    /* Table Rows */
    .custom-sales-tr { transition: background-color 0.1s ease-in-out; }
    .custom-sales-tr:hover { background-color: #f9fafb; }
    .dark .custom-sales-tr:hover { background-color: rgba(255, 255, 255, 0.05); }

    /* Table Cells */
    .custom-sales-td {
        padding: 0.75rem 1rem;
        color: #374151;
        border-bottom: 1px solid #e5e7eb;
    }
    .dark .custom-sales-td {
        color: #d1d5db;
        border-bottom-color: rgba(255, 255, 255, 0.05);
    }
    
    /* Remove bottom border from the very last row */
    .custom-sales-tr:last-child .custom-sales-td { border-bottom: none; }

    /* The Quantity Badge */
    .custom-qty-badge {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        border-radius: 9999px;
        background-color: #f3f4f6;
        padding: 0.125rem 0.625rem;
        font-size: 0.75rem;
        font-weight: 600;
        color: #1f2937;
    }
    .dark .custom-qty-badge {
        background-color: #1f2937;
        color: #d1d5db;
    }

    /* The Orange Total Text */
    .custom-total-text {
        font-weight: 600;
        color: #ea580c; /* Matches your POS checkout button orange */
    }
    .dark .custom-total-text {
        color: #fb923c;
    }

    /* Utility Helpers */
    .align-right { text-align: right; }
    .align-center { text-align: center; }
</style>

<div class="custom-sales-wrapper">
    <table class="custom-sales-table">
        <thead class="custom-sales-thead">
            <tr>
                <th class="custom-sales-th">Product</th>
                <th class="custom-sales-th align-right">Quantity</th>
                <th class="custom-sales-th align-right">Total</th>
            </tr>
        </thead>
        <tbody>
            @forelse($items as $item)
                <tr class="custom-sales-tr">
                    
                    <td class="custom-sales-td">
                        {{ $item->name }}
                    </td>
                    
                    <td class="custom-sales-td align-right">
                        <span class="custom-qty-badge">
                            {{ $item->total_qty }}
                        </span>
                    </td>
                    
                    <td class="custom-sales-td align-right custom-total-text">
                        Rp {{ number_format($item->total_sales, 0, ',', '.') }}
                    </td>
                    
                </tr>
            @empty
                <tr class="custom-sales-tr">
                    <td colspan="3" class="custom-sales-td align-center" style="padding: 2rem 1rem; color: #6b7280;">
                        No Sale.
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>