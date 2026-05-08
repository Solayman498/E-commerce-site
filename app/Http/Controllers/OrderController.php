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
        // 1.data validation (JS থেকে আসা ডাটা যাচাই করা)
        $request->validate([
            'cart_data' => 'required', 
            'total_amount' => 'required|numeric',
        ]);

        $user = auth()->user();
        $cartItems = json_decode($request->cart_data, true);

        // 2. Start database transaction (যাতে কোনো এরর হলে অর্ডার অর্ধেক সেভ না হয়)
        DB::beginTransaction();

        try {
            // 3. মূল অর্ডার তৈরি (Snapshotting user profile)
            $order = Order::create([
                'user_id' => $user->id,
                'order_number' => 'PS-' . strtoupper(Str::random(8)), // ইউনিক আইডি জেনারেট
                'total_amount' => $request->total_amount,
                'shipping_name' => $user->name,      // প্রোফাইল থেকে স্ন্যাপশট
                'shipping_phone' => $user->phone ?? 'N/A', 
                'shipping_address' => $user->address ?? 'N/A',
                'status' => 'pending',
                'payment_status' => 'unpaid',
            ]);

            // 4. অর্ডারের ভেতরের আইটেমগুলো সেভ করা (Looping through cart)
            foreach ($cartItems as $item) {
                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $item['id'],
                    'quantity' => $item['qty'],
                    'price' => $item['price'],
                ]);
            }

            DB::commit(); // সবকিছু ঠিক থাকলে ডাটাবেসে পারমানেন্টলি সেভ করো

            return response()->json([
                'success' => true,
                'message' => 'Order placed successfully!',
                'order_id' => $order->id
            ]);

        } catch (\Exception $e) {
            DB::rollBack(); // এরর হলে আগের সব সেভ ক্যানসেল করো
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    // শিপিং ডিটেইলস পেজ দেখার মেথড
    public function shippingDetails()
    {
        // ইউজারের লেটেস্ট অর্ডারটি নিয়ে আসা
        $order = Order::where('user_id', auth()->id())
                      ->with('items.product') // রিলেশনশিপ লোড করা
                      ->latest()
                      ->first();

        if (!$order) {
            return view('orders.shipping', ['no_order' => true]);
        }

        return view('orders.shipping', compact('order'));
    }
}