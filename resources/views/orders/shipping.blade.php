@extends('layouts.app')

@section('title', 'PetShop — Track Order')


@section('content')
<div class="py-12 bg-gray-50 min-h-screen">
    <div class="max-w-4xl mx-auto px-4">
        @if(isset($no_order))
            <div class="text-center py-20 bg-white rounded-3xl shadow-sm px-4">
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
                <div class="bg-blue-600 p-6 sm:p-8 text-white flex justify-between items-center order-header-flex">
                    <div>
                        <p class="text-blue-100 uppercase text-xs font-bold tracking-widest mb-1">
                            <i class="fa-solid fa-hashtag mr-1"></i> Order Number
                        </p>
                        <h1 class="text-xl sm:text-2xl font-bold">{{ $order->order_number }}</h1>
                    </div>
                    <div class="text-right order-header-right">
                        <p class="text-blue-100 uppercase text-xs font-bold tracking-widest mb-2">
                            <i class="fa-solid fa-info-circle mr-1"></i> Status
                        </p>
                        <span class="bg-white text-blue-600 px-4 py-1.5 rounded-full font-bold uppercase text-xs sm:text-sm shadow-sm">
                            {{ $order->status }}
                        </span>
                    </div>
                </div>

                <div class="p-6 sm:p-8">
                    <h2 class="text-lg font-bold mb-8 text-gray-800">
                        <i class="fa-solid fa-truck-fast mr-2 text-blue-600"></i> Shipping Progress
                    </h2>
                    
                    <div class="timeline-container">
                        <div class="timeline-line-base"></div>

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

                        <div class="timeline-line-progress" style="width: {{ ($currentLevel - 1) * 33.33 }}%"></div>

                        @foreach($steps as $index => $step)
                            @php 
                                $stepLevel = $statusHierarchy[$step['id']]; 
                                $isCompleted = $currentLevel >= $stepLevel;
                            @endphp
                            
                            <div class="timeline-step-item flex flex-col items-center relative z-10 flex-1 text-center">
                                <div class="w-10 h-10 rounded-full flex items-center justify-center font-bold text-white shadow-md transition-all duration-300
                                    {{ $isCompleted ? 'bg-green-500 ring-4 ring-green-100' : 'bg-gray-300' }}">
                                    <i class="{{ $step['icon'] }} text-sm"></i>
                                </div>
                                
                                <div class="timeline-text-group">
                                    <p class="mt-2.5 font-bold text-sm transition-all duration-300 
                                        {{ $isCompleted ? 'text-green-600' : 'text-gray-400' }}">
                                        {{ $step['label'] }}
                                    </p>
                                    @if($isCompleted)
                                        <p class="text-xs text-green-500 mt-0.5 flex items-center justify-center sm:justify-start gap-1">
                                            <i class="fa-solid fa-check-circle"></i> <span class="text-[11px] font-medium">Completed</span>
                                        </p>
                                    @endif
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>

                <div class="border-t p-6 sm:p-8">
                    <h3 class="font-bold mb-5 flex items-center text-gray-800">
                        <i class="fa-solid fa-box mr-2 text-blue-600"></i> Package Items
                    </h3>
                    <div class="space-y-4">
                        @foreach($order->items as $item)
                            <div class="flex items-center gap-4 pb-4 border-b last:border-0 hover:bg-gray-50 p-2 rounded-xl transition duration-300 package-item-flex">
                                <img src="{{ asset('storage/products/'.$item->product->image) }}" class="w-16 h-16 rounded-xl object-cover shadow-sm bg-gray-100 flex-shrink-0" onerror="this.src='https://via.placeholder.com/64'">
                                <div class="flex-1">
                                    <h4 class="font-bold text-gray-800 text-sm sm:text-base mb-1">{{ $item->product->name }}</h4>
                                    <div class="flex flex-wrap items-center gap-y-1 text-xs text-gray-500 font-medium">
                                        <span class="whitespace-nowrap"><i class="fa-solid fa-cube mr-1 text-gray-400"></i> Qty: {{ $item->quantity }}</span>
                                        <span class="mx-2 text-gray-300">|</span>
                                        <span class="whitespace-nowrap"><i class="fa-solid fa-tag mr-1 text-gray-400"></i> {{ number_format($item->price, 0) }} BDT</span>
                                    </div>
                                </div>
                                <div class="text-right package-item-right">
                                    <span class="text-sm font-bold text-gray-800">{{ number_format($item->quantity * $item->price, 0) }} BDT</span>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <div class="mt-6 pt-4 border-t bg-gray-50 p-4 rounded-xl text-sm sm:text-base">
                        <div class="flex justify-between items-center mb-2.5">
                            <span class="text-gray-600">Subtotal:</span>
                            <span class="font-bold text-gray-800">{{ number_format($order->items->sum(function($item) { return $item->quantity * $item->price; }), 0) }} BDT</span>
                        </div>
                        <div class="flex justify-between items-center mb-2.5">
                            <span class="text-gray-600">
                                <i class="fa-solid fa-truck mr-1 text-gray-400"></i> Delivery Fee:
                            </span>
                            <span class="font-bold text-gray-800">60 BDT</span>
                        </div>
                        <div class="flex justify-between items-center pt-2.5 border-t border-gray-200">
                            <span class="font-bold text-gray-800">Total Amount:</span>
                            <span class="text-lg sm:text-xl font-bold text-blue-600">
                                {{ number_format($order->items->sum(function($item) { return $item->quantity * $item->price; }) + 60, 0) }} BDT
                            </span>
                        </div>
                    </div>
                </div>

                <div class="border-t p-6 sm:p-8 bg-gray-50 flex justify-between items-center action-btn-flex">
                    <a href="{{ route('orders.index') }}" class="text-blue-600 hover:text-blue-800 font-bold flex items-center text-sm sm:text-base transition">
                        <i class="fa-solid fa-arrow-left mr-2"></i> Back to My Orders
                    </a>
                    @if($order->status == 'delivered')
                        <a href="#" class="bg-green-600 text-white px-6 py-2.5 rounded-xl font-bold hover:bg-green-700 transition duration-300 flex items-center text-sm sm:text-base shadow-sm">
                            <i class="fa-solid fa-star mr-2"></i> Write a Review
                        </a>
                    @endif
                </div>
            </div>
        @endif
    </div>
</div>
@endsection