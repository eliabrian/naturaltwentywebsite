<?php

use App\Http\Controllers\BoardGameController;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\MenuController;
use App\Livewire\Customer\MenuBrowser;
use Illuminate\Support\Facades\Route;

Route::view('/', 'home')->name('home');

Route::get('/booking', [BookingController::class, 'create'])->name('bookings.create');
Route::post('/booking', [BookingController::class, 'store'])->name('bookings.store');

Route::get('/games', [BoardGameController::class, 'index'])->name('games.index');

Route::get('/menu', [MenuController::class, 'index'])->name('menu.index');


Route::post('/midtrans/callback', function (Request $request) {
    $serverKey = env('MIDTRANS_SERVER_KEY');
    
    // 1. Verify Midtrans SHA512 Signature
    $hashed = hash("sha512", $request->order_id . $request->status_code . $request->gross_amount . $serverKey);
    
    if ($hashed !== $request->signature_key) {
        return response()->json(['message' => 'Invalid signature'], 403);
    }

    // 2. Extract the real Order ID (removing the "-time()" we added)
    $realOrderId = explode('-', $request->order_id)[0];
    
    /** @var \App\Models\Order $order */
    $order = Order::find($realOrderId);

    if (!$order) {
        return response()->json(['message' => 'Order not found'], 404);
    }

    // 3. Handle Midtrans "settlement" (which means successfully paid for QRIS)
    if ($request->transaction_status == 'settlement' && $order->status === 'awaiting_payment') {
        
        $order->update(['status' => 'pending']);
        
        // 🚀 Send to Kitchen and Auto-Close POS Terminal!
        broadcast(new OrderPlaced($order));
        
        return response()->json(['message' => 'Success']);
    }

    return response()->json(['message' => 'Status ignored']);
});

Route::get('/order/{token}', MenuBrowser::class)->name('customer.order');