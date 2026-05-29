@extends('layouts.app')

@section('content')
<div class="py-12 bg-gray-50 min-height-screen">
    <div class="max-w-4xl mx-auto px-4">
        @if(isset($no_order))
            <div class="text-center py-20 bg-white rounded-3xl shadow-sm">
                <div class="text-6xl mb-4 text-gray-400">
                    <i class="fa-solid fa-box-open"></i>
                </div>
                <h2 class="text-2xl font-bold text-gray-800">You haven't ordered anything yet!</h2>
                <a href="/" class="mt-6 inline-block bg-blue-600 text-white px-8 py-3 rounded-xl font-bold hover:bg-blue-700 transition duration-300">
                    <i class="fa-solid fa-cart-shopping mr-2"></i> Start Shopping
                </a>
            </div>
        @else
            <div class="bg-white rounded-3xl shadow-sm overflow-hidden mb-8">
                <div class="bg-blue-600 p-8 text-white flex justify-between items-center">
                    <div>
                        <p class="text-blue-100 uppercase text-xs font-bold tracking-widest">
                            <i class="fa-solid fa-hashtag mr-1"></i> Order Number
                        </p>
                        <h1 class="text-2xl font-bold">{{ $order->order_number }}</h1>
                    </div>
                    <div class="text-right">
                        <p class="text-blue-100 uppercase text-xs font-bold tracking-widest">
                            <i class="fa-solid fa-info-circle mr-1"></i> Status
                        </p>
                        <span class="bg-white text-blue-600 px-4 py-1 rounded-full font-bold uppercase text-sm">
                            {{ $order->status }}
                        </span>
                    </div>
                </div>

                <!-- Tracking Timeline -->
                <div class="p-8">
                    <h2 class="text-lg font-bold mb-10 text-gray-800">
                        <i class="fa-solid fa-truck-fast mr-2 text-blue-600"></i> Shipping Progress
                    </h2>
                    <div class="relative flex justify-between items-start">
                        <!-- Line -->
                        <div class="absolute top-5 left-0 w-full h-1 bg-gray-200 -z-0 rounded-full"></div>

                        <!-- Steps & Hierarchy Definition -->
                        @php
                            $steps = [
                                ['id' => 'pending', 'label' => 'Placed', 'icon' => 'fa-solid fa-file-alt'],
                                ['id' => 'processing', 'label' => 'Processing', 'icon' => 'fa-solid fa-gear'],
                                ['id' => 'shipped', 'label' => 'Shipped', 'icon' => 'fa-solid fa-truck'],
                                ['id' => 'delivered', 'label' => 'Delivered', 'icon' => 'fa-solid fa-house-chimney']
                            ];

                            $statusHierarchy = [
                                'pending'    => 1,
                                'processing' => 2,
                                'shipped'    => 3,
                                'delivered'  => 4
                            ];

                            $currentLevel = $statusHierarchy[$order->status] ?? 1;
                        @endphp

                        <!-- Progress Line -->
                        <div class="absolute top-5 left-0 h-1 bg-green-500 transition-all duration-500 rounded-full" 
                            style="width: {{ ($currentLevel - 1) * 33.33 }}%">
                        </div>

                        @foreach($steps as $index => $step)
                            @php 
                                $stepLevel = $statusHierarchy[$step['id']]; 
                            @endphp
                            <div class="flex flex-col items-center relative z-10 flex-1">

                                <div class="w-10 h-10 rounded-full flex items-center justify-center font-bold text-white shadow-lg transition-all duration-300
                                    {{ $currentLevel >= $stepLevel ? 'bg-green-500' : 'bg-gray-300' }}">
                                    <i class="{{ $step['icon'] }} text-sm"></i>
                                </div>
                                <p class="mt-3 font-bold text-sm transition-all duration-300 
                                    {{ $currentLevel >= $stepLevel ? 'text-green-600' : 'text-gray-400' }}">
                                    {{ $step['label'] }}
                                </p>
                                @if($currentLevel >= $stepLevel && $stepLevel < 4)
                                    <p class="text-xs text-green-500 mt-1">
                                        <i class="fa-solid fa-check-circle"></i> Completed
                                    </p>
                                @endif
                            </div>
                        @endforeach
                    </div>
                </div>

                <!-- Order Items -->
                <div class="border-t p-8">
                    <h3 class="font-bold mb-4 flex items-center">
                        <i class="fa-solid fa-package mr-2 text-blue-600"></i> Package Items
                    </h3>
                    <div class="space-y-4">
                        @foreach($order->items as $item)
                            <div class="flex items-center gap-4 pb-4 border-b last:border-0 hover:bg-gray-50 p-2 rounded-lg transition duration-300">
                                <img src="{{ asset('storage/products/'.$item->product->image) }}" class="w-16 h-16 rounded-lg object-cover shadow-sm">
                                <div class="flex-1">
                                    <h4 class="font-bold text-gray-800">{{ $item->product->name }}</h4>
                                    <p class="text-sm text-gray-500">
                                        <i class="fa-solid fa-cube mr-1"></i> Qty: {{ $item->quantity }} 
                                        <span class="mx-2">|</span>
                                        <i class="fa-solid fa-tag mr-1"></i> {{ $item->price }} BDT 
                                        <span class="mx-2">|</span>
                                        <i class="fa-solid fa-calculator mr-1"></i> Total: {{ $item->quantity * $item->price }} BDT
                                    </p>
                                </div>
                                <div class="text-right">
                                    <span class="text-sm font-bold text-gray-700">{{ $item->quantity * $item->price }} BDT</span>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <!-- Order Summary -->
                    <div class="mt-6 pt-4 border-t bg-gray-50 p-4 rounded-xl">
                        <div class="flex justify-between items-center mb-2">
                            <span class="text-gray-600">Subtotal:</span>
                            <span class="font-bold">{{ $order->items->sum(function($item) { return $item->quantity * $item->price; }) }} BDT</span>
                        </div>
                        <div class="flex justify-between items-center mb-2">
                            <span class="text-gray-600">
                                <i class="fa-solid fa-truck mr-1"></i> Delivery Fee:
                            </span>
                            <span class="font-bold">60 BDT</span>
                        </div>
                        <div class="flex justify-between items-center pt-2 border-t">
                            <span class="text-lg font-bold text-gray-800">Total:</span>
                            <span class="text-xl font-bold text-blue-600">
                                {{ $order->items->sum(function($item) { return $item->quantity * $item->price; }) + 60 }} BDT
                            </span>
                        </div>
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="border-t p-8 bg-gray-50 flex justify-between items-center">
                    <a href="/" class="text-blue-600 hover:text-blue-800 font-bold flex items-center">
                        <i class="fa-solid fa-arrow-left mr-2"></i> Continue Shopping
                    </a>
                    @if($order->status == 'delivered')
                        <a href="{{ route('orders.review', $order->id) }}" class="bg-green-600 text-white px-6 py-2 rounded-xl font-bold hover:bg-green-700 transition duration-300 flex items-center">
                            <i class="fa-solid fa-star mr-2"></i> Write a Review
                        </a>
                    @endif
                </div>
            </div>
        @endif
    </div>
</div>
@endsection