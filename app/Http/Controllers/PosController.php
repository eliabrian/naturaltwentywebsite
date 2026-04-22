<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Events\OrderPlaced;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PosController extends Controller
{
    public function store(Request $request)
    {
        $validated = $request->validate([
            'customer_name' => 'nullable|string',
            'total_amount' => 'required|numeric',
            'items' => 'required|array',
            'items.*.menu_item_id' => 'required|exists:menu_items,id',
            'items.*.quantity' => 'required|integer|min:1',
            'items.*.price' => 'required|numeric',
            'items.*.special_instructions' => 'nullable|string',
        ]);

        $order = DB::transaction(function () use ($validated) {
            $order = Order::create([
                'customer_name' => $validated['customer_name'] ?? 'Guest',
                'total_amount' => $validated['total_amount'],
                'status' => 'pending',
            ]);

            foreach ($validated['items'] as $item) {
                $order->items()->create($item);
            }

            return $order;
        });

        broadcast(new OrderPlaced($order));

        return response()->json([
            'message' => 'Order successfully placed!',
            'order' => $order
        ], 201);
    }
}
