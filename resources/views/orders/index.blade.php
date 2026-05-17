@extends('layouts.app')

@section('content')
<div class="py-12 bg-gray-50 min-h-screen">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <h1 class="text-3xl font-bold mb-8">My Orders</h1>

        <!-- সফলভাবে ডিলিট হওয়ার পর ফ্ল্যাশ মেসেজ দেখানোর জন্য -->
        @if(session('success'))
            <div class="mb-4 p-4 bg-green-100 text-green-700 rounded-xl font-medium shadow-sm">
                {{ session('success') }}
            </div>
        @endif

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
                            <th class="p-4 font-bold">Payment Status</th>
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
                                <span class="px-3 py-1 rounded-full text-xs font-bold uppercase 
                                    {{ $order->payment_status == 'paid' ? 'bg-green-100 text-green-600' : 'bg-red-100 text-red-600' }}">
                                    {{ $order->payment_status }}
                                </span>
                            </td>
                            <td class="p-4">
                                <div class="flex items-center gap-2">
                                    <!-- Track Order Button -->
                                    <a href="{{ route('order.shipping', $order->id) }}" class="inline-block bg-blue-600 text-white px-4 py-2 rounded-lg text-sm font-bold hover:bg-blue-700 transition whitespace-nowrap">
                                        Track Order
                                    </a>

                                    <!-- Delete Button (Only visible if status is delivered) -->
                                    @if($order->status == 'delivered')
                                        <form id="hide-form-{{ $order->id }}" action="{{ route('order.hide', $order->id) }}" method="POST" style="display: none;">
                                            @csrf
                                        </form>
                                            <button type="button" onclick="confirmDelete({{ $order->id }})" class="bg-red-500 text-white px-3 py-1.5 rounded-lg text-sm font-bold hover:bg-red-600">
                                                🗑 Delete
                                            </button>
                                        </form>
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
<!-- SweetAlert2 CDN আপনার layouts/app.blade.php এর মাথায় রাখতে পারেন -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
function confirmDelete(orderId) {
    Swal.fire({
        title: 'Are you sure?',
        text: "You won't be able to revert this order history!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Yes, delete it!'
    }).then((result) => {
        if (result.isConfirmed) {
            // যদি ইউজার 'Yes' এ ক্লিক করে, তবে ফর্মটি সাবমিট হবে
            document.getElementById('hide-form-' + orderId).submit();
        }
    })
}
</script>
@endpush