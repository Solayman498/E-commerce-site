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
    // 1. create payment and order (For AJAX Fetch)
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

            // save order items
            foreach ($cartItems as $item) {
                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $item['id'],
                    'quantity' => $item['qty'],
                    'price' => $item['price'],
                ]);
            }

            
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
            return response()->json(['success' => false, 'message' => 'bKash URL generation failed']);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    // 2.  (Callback)
    public function callback(Request $request)
    {
        // if failed or cancelled by user
        if (in_array($request->status, ['failure', 'cancel'])) {
            return redirect()->route('orders.index')->with('error', 'Payment cancelled or failed.');
        }

        $paymentID = $request->paymentID;
        $execute = Bkash::executePayment($paymentID);

        // '0000' its means payment is successful and verified by bKash
        if (isset($execute['statusCode']) && $execute['statusCode'] === '0000') {

            // Find the order ID sent to us from bKash
            $orderId = $execute['merchantInvoiceNumber'] ?? null;
            
            if ($orderId) {
                $order = Order::find($orderId);
                if ($order) {
                    // database update 
                    $order->update([
                        'payment_status' => 'paid'
                    ]);
                }
            }

            // show data on success page
            session()->put('bkash_success', [
                'trxID'      => $execute['trxID'],
                'amount'     => $execute['amount'],
                'order_no'   => $order ? $order->order_number : 'N/A',
            ]);

            return redirect()->route('bkash.success');
        }

        return redirect()->route('orders.index')->with('error', 'Payment verification failed.');
    }

    // 3.payment success page
    public function success()
    {
        if (!session()->has('bkash_success')) {
            return redirect()->route('orders.index');
        }

        $data = session('bkash_success');
        session()->forget('bkash_success');
        
        return view('success', compact('data')); // resources/views/success.blade.php
    }
}