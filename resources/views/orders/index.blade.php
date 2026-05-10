@extends('layouts.app')

@section('content')
<div class="py-12 bg-gray-50 min-h-screen">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <h1 class="text-3xl font-bold mb-8">My Orders</h1>

        @if($orders->isEmpty())
            <div class="bg-white p-10 text-center rounded-2xl shadow-sm">
                <p class="text-gray-500">No orders found.</p>
            </div>
        @else
            <div class="bg-white shadow-sm rounded-2xl overflow-hidden">
                <table class="w-full text-left">
                    <thead class="bg-gray-100 border-b">
                        <tr>
                            <th class="p-4 font-bold">Order ID</th>
                            <th class="p-4 font-bold">Date</th>
                            <th class="p-4 font-bold">Total</th>
                            <th class="p-4 font-bold">Status</th>
                            <th class="p-4 font-bold">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($orders as $order)
                        <tr class="border-b hover:bg-gray-50 transition">
                            <td class="p-4 font-medium text-blue-600">{{ $order->order_number }}</td>
                            <td class="p-4 text-gray-600">{{ $order->created_at->format('d M, Y') }}</td>
                            <td class="p-4 font-bold">{{ number_format($order->total_amount, 0) }} BDT</td>
                            <td class="p-4">
                                <span class="px-3 py-1 rounded-full text-xs font-bold uppercase 
                                    {{ $order->status == 'delivered' ? 'bg-green-100 text-green-600' : 'bg-blue-100 text-blue-600' }}">
                                    {{ $order->status }}
                                </span>
                            </td>
                            <td class="p-4">
                               <a href="{{ route('order.shipping', $order->id) }}" class="bg-blue-600 text-white px-4 py-2 rounded-lg text-sm font-bold hover:bg-blue-700">
                                    Track Order
                                </a>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>
</div>
@endsection