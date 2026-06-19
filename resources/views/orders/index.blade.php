@extends('layouts.app')

@section('title', 'PetShop — My Orders')

@push('styles')
<style>

    .table-responsive-wrapper {
        width: 100%;
        overflow-x: auto;
        -webkit-overflow-scrolling: touch;
        background: #ffffff;
        border-radius: 16px;
        box-shadow: 0 1px 3px rgba(0,0,0,0.05);
    }
    

    .whitespace-nowrap {
        white-space: nowrap;
    }

    @media (max-width: 640px) {
        .mobile-header-spacing {
            padding-left: 16px;
            padding-right: 16px;
            margin-bottom: 24px !important;
        }
        .text-3xl {
            font-size: 1.5rem !important;
        }
    }
</style>
@endpush

@section('content')
<div class="py-12 bg-gray-50 min-h-screen">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <h1 class="text-3xl font-bold mb-8 mobile-header-spacing">📦 My Orders</h1>

        @if(session('success'))
            <div class="mb-4 p-4 bg-green-100 text-green-700 rounded-xl font-medium shadow-sm mx-2 sm:mx-0">
                {{ session('success') }}
            </div>
        @endif

        @if($orders->isEmpty())
            <div class="bg-white p-10 text-center rounded-2xl shadow-sm mx-2 sm:mx-0">
                <p class="text-gray-500 text-lg">No orders found yet.</p>
                <a href="{{ route('products.index') }}" class="inline-block mt-4 bg-blue-600 text-white px-5 py-2.5 rounded-xl font-bold text-sm hover:bg-blue-700 transition">Shop Now</a>
            </div>
        @else
            <div class="table-responsive-wrapper">
                <table class="w-full text-left min-w-[800px]">
                    <thead class="bg-gray-100 border-b">
                        <tr>
                            <th class="p-4 font-bold text-gray-700">Order ID</th>
                            <th class="p-4 font-bold text-gray-700">Date</th>
                            <th class="p-4 font-bold text-gray-700">Total</th>
                            <th class="p-4 font-bold text-gray-700">Status</th>
                            <th class="p-4 font-bold text-gray-700">Payment Status</th>
                            <th class="p-4 font-bold text-gray-700 text-right pr-6">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($orders as $order)
                        <tr class="border-b hover:bg-gray-50 transition">
                            <td class="p-4 font-medium text-blue-600 whitespace-nowrap">#{{ $order->order_number }}</td>
                            
                            <td class="p-4 text-gray-600 whitespace-nowrap">{{ $order->created_at->format('d M, Y') }}</td>
                            
                            <td class="p-4 font-bold text-gray-800 whitespace-nowrap">{{ number_format($order->total_amount, 0) }} BDT</td>
                            
                            <td class="p-4 whitespace-nowrap">
                                <span class="px-3 py-1 rounded-full text-xs font-bold uppercase tracking-wider
                                    {{ $order->status == 'delivered' ? 'bg-green-100 text-green-600' : 'bg-blue-100 text-blue-600' }}">
                                    {{ $order->status }}
                                </span>
                            </td>
                            
                            <td class="p-4 whitespace-nowrap">
                                <span class="px-3 py-1 rounded-full text-xs font-bold uppercase tracking-wider
                                    {{ $order->payment_status == 'paid' ? 'bg-green-100 text-green-600' : 'bg-red-100 text-red-600' }}">
                                    {{ $order->payment_status }}
                                </span>
                            </td>
                            
                            <td class="p-4 text-right pr-6 whitespace-nowrap">
                                <div class="flex items-center justify-end gap-2">
                                    <a href="{{ route('order.shipping', $order->id) }}" class="inline-flex items-center bg-blue-600 text-white px-4 py-2 rounded-lg text-sm font-bold hover:bg-blue-700 transition">
                                        <i class="fa-solid fa-location-dot mr-1.5"></i> Track Order
                                    </a>

                                    @if($order->status == 'delivered')
                                        <form id="hide-form-{{ $order->id }}" action="{{ route('order.hide', $order->id) }}" method="POST" style="display: none;">
                                            @csrf
                                        </form>
                                        <button type="button" onclick="confirmDelete({{ $order->id }})" class="inline-flex items-center bg-red-500 text-white px-3 py-2 rounded-lg text-sm font-bold hover:bg-red-600 transition">
                                            <i class="fa-solid fa-trash mr-1"></i> Delete
                                        </button>
                                    @endif
                                </div>
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

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
function confirmDelete(orderId) {
    Swal.fire({
        title: 'Are you sure?',
        text: "You won't be able to revert this from your order history!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Yes, delete it!',
        cancelButtonText: 'Cancel'
    }).then((result) => {
        if (result.isConfirmed) {
            const form = document.getElementById('hide-form-' + orderId);
            if (form) {
                form.submit();
            }
        }
    })
}
</script>
@endpush