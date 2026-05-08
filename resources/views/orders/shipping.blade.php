@extends('layouts.app')

@section('content')
<div class="py-12 bg-gray-50 min-height-screen">
    <div class="max-w-4xl mx-auto px-4">
        @if(isset($no_order))
            <div class="text-center py-20 bg-white rounded-3xl shadow-sm">
                <div class="text-6xl mb-4">📦</div>
                <h2 class="text-2xl font-bold text-gray-800">You haven't ordered anything yet!</h2>
                <a href="/" class="mt-6 inline-block bg-blue-600 text-white px-8 py-3 rounded-xl font-bold">Start Shopping</a>
            </div>
        @else
            <div class="bg-white rounded-3xl shadow-sm overflow-hidden mb-8">
                <div class="bg-blue-600 p-8 text-white flex justify-between items-center">
                    <div>
                        <p class="text-blue-100 uppercase text-xs font-bold tracking-widest">Order Number</p>
                        <h1 class="text-2xl font-bold">{{ $order->order_number }}</h1>
                    </div>
                    <div class="text-right">
                        <p class="text-blue-100 uppercase text-xs font-bold tracking-widest">Status</p>
                        <span class="bg-white text-blue-600 px-4 py-1 rounded-full font-bold uppercase text-sm">
                            {{ $order->status }}
                        </span>
                    </div>
                </div>

                <!-- Tracking Timeline -->
                <div class="p-8">
                    <h2 class="text-lg font-bold mb-10 text-gray-800">Shipping Progress</h2>
                    <div class="relative flex justify-between items-start">
                        <!-- Line -->
                        <div class="absolute top-5 left-0 w-full h-1 bg-gray-200 -z-0"></div>
                        <div class="absolute top-5 left-0 h-1 bg-green-500 transition-all duration-500 -z-0" 
                             style="width: {{ $order->status == 'pending' ? '0%' : ($order->status == 'processing' ? '33%' : ($order->status == 'shipped' ? '66%' : '100%')) }}">
                        </div>

                        <!-- Steps -->
                        @php
                            $steps = [
                                ['id' => 'pending', 'label' => 'Placed', 'icon' => '📝'],
                                ['id' => 'processing', 'label' => 'Processing', 'icon' => '⚙️'],
                                ['id' => 'shipped', 'label' => 'Shipped', 'icon' => '🚚'],
                                ['id' => 'delivered', 'label' => 'Delivered', 'icon' => '🏠']
                            ];
                            $currentStatusIndex = array_search($order->status, array_column($steps, 'id'));
                        @endphp

                        @foreach($steps as $index => $step)
                            <div class="flex flex-col items-center relative z-10">
                                <div class="w-10 h-10 rounded-full flex items-center justify-center font-bold text-white shadow-lg
                                    {{ $index <= $currentStatusIndex ? 'bg-green-500' : 'bg-gray-300' }}">
                                    {{ $step['icon'] }}
                                </div>
                                <p class="mt-3 font-bold text-sm {{ $index <= $currentStatusIndex ? 'text-green-600' : 'text-gray-400' }}">
                                    {{ $step['label'] }}
                                </p>
                            </div>
                        @endforeach
                    </div>
                </div>

                <!-- Order Items -->
                <div class="border-t p-8">
                    <h3 class="font-bold mb-4">Package Items</h3>
                    @foreach($order->items as $item)
                        <div class="flex items-center gap-4 mb-4 pb-4 border-b last:border-0">
                            <img src="{{ asset('storage/products/'.$item->product->image) }}" class="w-16 h-16 rounded-lg object-cover">
                            <div class="flex-1">
                                <h4 class="font-bold text-gray-800">{{ $item->product->name }}</h4>
                                <p class="text-sm text-gray-500">Qty: {{ $item->quantity }} x {{ $item->price }} BDT</p>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif
    </div>
</div>
@endsection