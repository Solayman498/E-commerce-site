<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Softrang\BkashPayment\Facades\Bkash;
use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;

class BkashController extends Controller
{
    // 1. Initiate bKash Payment and Create Pending Order
    public function create(Request $request)
    {
        $request->validate([
            'cart_data' => 'required',
            'total_amount' => 'required|numeric|min:1',
        ]);

        $user = auth()->user();
        $cartItems = json_decode($request->cart_data, true);

        DB::beginTransaction();

        try {
            $order = Order::create([
                'user_id' => $user->id,
                'order_number' => 'PS-' . strtoupper(Str::random(8)),
                'total_amount' => $request->total_amount,
                'shipping_name' => $user->name,
                'shipping_phone' => $user->phone ?? 'N/A',
                'shipping_address' => $user->address ?? 'N/A',
                'status' => 'pending',
                'payment_method' => 'bkash',
                'payment_status' => 'unpaid',
            ]);

            foreach ($cartItems as $item) {
                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $item['id'],
                    'quantity' => $item['qty'],
                    'price' => $item['price'],
                ]);
            }

            // Store the current Order ID inside Laravel Session for tracking
            session()->put('current_bkash_order_id', $order->id);

            $response = Bkash::createPayment([
                'amount' => $order->total_amount,
                'payerReference' => $user->phone ?? '01700000000',
                'merchantInvoiceNumber' => $order->id 
            ]);

            if (isset($response['bkashURL'])) {
                DB::commit(); 
                return response()->json([
                    'success' => true,
                    'payment_url' => $response['bkashURL']
                ]);
            }

            DB::rollBack();
            session()->forget('current_bkash_order_id');
            return response()->json(['success' => false, 'message' => 'bKash URL generation failed']);

        } catch (\Exception $e) {
            DB::rollBack();
            session()->forget('current_bkash_order_id');
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    // 2. Handle bKash Callback Responses (Success/Fail/Cancel)
    public function callback(Request $request)
    {
        // Get the Order ID from Session or Request fallback
        $orderId = session()->get('current_bkash_order_id') ?? $request->merchantInvoiceNumber;

        // Clean up and delete order if payment fails or is canceled
        if (in_array($request->status, ['failure', 'cancel'])) {
            if ($orderId) {
                $order = Order::find($orderId);
                if ($order) {
                    OrderItem::where('order_id', $order->id)->delete();
                    $order->delete(); 
                }
                session()->forget('current_bkash_order_id');
            }
            return redirect()->route('checkout')->with('error', 'bKash Payment ' . ucfirst($request->status) . 'ed. Order canceled.');
        }

        $paymentID = $request->paymentID;
        $execute = Bkash::executePayment($paymentID);

        // Process successful payment validation
        if (isset($execute['statusCode']) && $execute['statusCode'] === '0000') {
            $orderId = $execute['merchantInvoiceNumber'] ?? $orderId;
            $order = null;
            
            if ($orderId) {
                $order = Order::find($orderId);
                if ($order) {
                    $order->update([
                        'payment_status' => 'paid',
                        'status' => 'pending'
                    ]);
                }
                session()->forget('current_bkash_order_id');
            }

            session()->put('bkash_success', [
                'trxID'      => $execute['trxID'],
                'amount'     => $execute['amount'],
                'order_no'   => $order ? $order->order_number : 'N/A',
            ]);

            return redirect()->route('bkash.success');
        }

        // Cleanup if bKash verification response fails
        if ($orderId) {
            $order = Order::find($orderId);
            if ($order) {
                OrderItem::where('order_id', $order->id)->delete();
                $order->delete();
            }
            session()->forget('current_bkash_order_id');
        }

        return redirect()->route('checkout')->with('error', 'Payment verification failed from bKash.');
    }

    // 3. Render Payment Success Page (The Missing Method Fix)
    public function success()
    {
        if (!session()->has('bkash_success')) {
            return redirect()->route('orders.index');
        }

        $data = session('bkash_success');
        session()->forget('bkash_success');
        
        return view('success', compact('data')); 
    }
}