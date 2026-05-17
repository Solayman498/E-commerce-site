@extends('layouts.app')

@section('content')
<div class="py-12 bg-gray-50">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <h1 class="text-3xl font-bold mb-8 text-gray-800">Complete Your Order</h1>
        
        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            <!-- Left: Shipping Info (From Profile) -->
            <div class="md:col-span-2">
                <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100">
                    <h2 class="text-xl font-semibold mb-6 flex items-center">
                        <span class="bg-blue-100 text-blue-600 p-2 rounded-lg mr-3">📍</span>
                        Shipping Information
                    </h2>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-medium text-gray-500">Full Name</label>
                            <p class="text-lg font-bold text-gray-800 border-b pb-2">{{ auth()->user()->name }}</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-500">Phone Number</label>
                            <p class="text-lg font-bold text-gray-800 border-b pb-2">{{ auth()->user()->phone ?? 'Add phone in profile' }}</p>
                        </div>
                        <div class="md:col-span-2">
                            <label class="block text-sm font-medium text-gray-500">Delivery Address</label>
                            <p class="text-lg font-bold text-gray-800 border-b pb-2">{{ auth()->user()->address ?? 'Add address in profile' }}</p>
                        </div>
                    </div>
                    
                    <p class="mt-4 text-sm text-blue-500 italic">* These details are taken from your profile. Update profile to change them.</p>
                </div>

                <!-- Payment Method Selection -->
            <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100 mt-6">
                <h2 class="text-xl font-semibold mb-4">Select Payment Method</h2>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <!-- Cash on Delivery -->
                    <label class="flex items-center p-4 border rounded-xl cursor-pointer hover:bg-gray-50 bg-white" id="codLabel">
                        <input type="radio" name="payment_method" value="cod" checked class="h-5 w-5 text-blue-600">>
                        <span class="ml-3 font-bold text-gray-700">Cash on Delivery</span>
                    </label>
                    
                    <!-- bKash -->
                    <label class="flex items-center p-4 border rounded-xl cursor-pointer hover:bg-gray-50 bg-white" id="bkashLabel">
                        <input type="radio" name="payment_method" value="bkash" class="h-5 w-5 text-blue-600">
                        <span class="ml-3 font-bold text-gray-700">bKash Payment</span>
                    </label>
                </div>
            </div>
            </div>

            <!-- Right: Order Summary -->
            <div class="md:col-span-1">
                <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100 sticky top-6">
                    <h2 class="text-xl font-semibold mb-6">Order Summary</h2>
                    <div id="checkoutSummaryItems" class="space-y-4 mb-6">
                        <!-- JS will inject items here -->
                    </div>
                    
                    <div class="border-t pt-4 space-y-2">
                        <div class="flex justify-between text-gray-600">
                            <span>Subtotal</span>
                            <span id="summarySubtotal">0.00 BDT</span>
                        </div>
                        <div class="flex justify-between text-gray-600">
                            <span>Delivery Fee</span>
                            <span>60.00 BDT</span>
                        </div>
                        <div class="flex justify-between text-xl font-bold text-gray-900 pt-2 border-t">
                            <span>Total</span>
                            <span id="summaryTotal">0.00 BDT</span>
                        </div>
                    </div>

                    <button onclick="placeOrder()" class="w-full bg-blue-600 hover:bg-blue-700 text-white font-bold py-4 rounded-xl mt-8 shadow-lg shadow-blue-200 transition-all">
                        Confirm Order
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection