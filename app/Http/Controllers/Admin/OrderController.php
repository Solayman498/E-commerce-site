<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function index()
    {  
        $orders = Order::with('user')->latest()->get();
        return view('admin.Track-order.ordertrack', compact('orders'));
    }

    public function update(Request $request, $id)
    {
        $order = Order::findOrFail($id);
        
        $order->update([
            'status' => $request->status,
            'payment_status' => $request->payment_status
        ]);

        return back()->with('success', 'Order #' . $order->order_number . ' updated successfully!');
    }
}
