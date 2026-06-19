@extends('layouts.app')

@section('content')
<div class="py-12 bg-gray-50 min-h-screen flex items-center justify-center">
    <div class="max-w-md w-full bg-white p-8 rounded-3xl shadow-lg border border-gray-100 text-center">
        <!-- Success Animation/Icon -->
        <div class="w-20 h-20 bg-green-100 text-green-600 rounded-full flex items-center justify-center mx-auto text-4xl mb-6 shadow-sm">
            ✓
        </div>
        
        <h1 class="text-3xl font-extrabold text-gray-900 mb-2">Payment Successful!</h1>
        <p class="text-gray-500 mb-8">Your order has been placed and payment is verified via bKash.</p>
        
        <!-- Order Memo / Receipt -->
        <div class="bg-gray-50 p-6 rounded-2xl text-left space-y-3 mb-8 border border-gray-100">
            <div class="flex justify-between text-sm">
                <span class="text-gray-500">Order Number:</span>
                <span class="font-bold text-gray-800">{{ $data['order_no'] }}</span>
            </div>
            <div class="flex justify-between text-sm">
                <span class="text-gray-500">bKash TrxID:</span>
                <span class="font-mono font-bold text-blue-600 uppercase">{{ $data['trxID'] }}</span>
            </div>
            <div class="flex justify-between text-sm border-t pt-3">
                <span class="text-gray-700 font-semibold">Amount Paid:</span>
                <span class="font-extrabold text-gray-900">{{ number_format($data['amount'], 2) }} BDT</span>
            </div>
        </div>
        
        <!-- Action Buttons -->
        <div class="space-y-3">
            <a href="{{ route('orders.index') }}" class="block w-full bg-blue-600 hover:bg-blue-700 text-white font-bold py-3 px-4 rounded-xl transition shadow-lg shadow-blue-100">
                📦 Track Your Order
            </a>
            <a href="/" class="block w-full bg-gray-100 hover:bg-gray-200 text-gray-700 font-bold py-3 px-4 rounded-xl transition">
                Continue Shopping
            </a>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    localStorage.removeItem('ps_cart');
</script>
@endpush