<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
{
    public function checkout()
    {
        return view('checkout.index');
    }

    public function store(Request $request)
    {
        // Validate request data
        $request->validate([
            'cart_data' => 'required', 
            'total_amount' => 'required|numeric',
        ]);

        $user = auth()->user();
        $cartItems = json_decode($request->cart_data, true);

        // Start database transaction
        DB::beginTransaction();

        try {
            // Create main order with user profile snapshot
            $order = Order::create([
                'user_id' => $user->id,
                'order_number' => 'PS-' . strtoupper(Str::random(8)),
                'total_amount' => $request->total_amount,
                'shipping_name' => $user->name,
                'shipping_phone' => $user->phone ?? 'N/A',
                'shipping_address' => $user->address ?? 'N/A',
                'status' => 'pending',
                'payment_status' => 'unpaid',
            ]);

            // Save order items
            foreach ($cartItems as $item) {
                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $item['id'],
                    'quantity' => $item['qty'],
                    'price' => $item['price'],
                ]);
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Order placed successfully!',
                'order_id' => $order->id
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    public function index()
    {
        // Fetch only orders that are visible to the user
        $orders = Order::where('user_id', auth()->id())
                    ->where('is_visible_to_user', true)
                    ->latest()
                    ->get();
        return view('orders.index', compact('orders'));
    }

    public function hideOrder($id)
    {
        $order = Order::where('user_id', auth()->id())->findOrFail($id);
        $order->update(['is_visible_to_user' => false]);

        return back()->with('success', 'Order removed from history.');
    }

    // Display shipping details page
    public function shippingDetails($id)
    {
        $order = Order::where('user_id', auth()->id())
                    ->where('id', $id)
                    ->with('items.product')
                    ->firstOrFail();

        return view('orders.shipping', compact('order'));
    }
}