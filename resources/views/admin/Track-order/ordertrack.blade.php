@extends('layouts.admin')

@section('admin_content')
<div class="admin-card">
    
    <div class="admin-header-flex" style="flex-direction: column; align-items: flex-start; gap: 4px;">
        <h2>Order Tracking Management</h2>
        <div class="admin-sub-text">Monitor customer orders, update shipping progress, and manage payment statuses.</div>
    </div>

    @if(session('success'))
        <div style="padding: 12px; background: #ecfdf5; color: #065f46; border: 1px solid #a7f3d0; border-radius: 6px; margin-bottom: 16px; font-size: 14px; font-weight: 600;">
            {{ session('success') }}
        </div>
    @endif

    <div class="admin-table-wrapper">
        <table class="admin-table">
            <thead>
                <tr>
                    <th>Order #</th>
                    <th>Customer</th>
                    <th>Total Amount</th>
                    <th>Shipping Status</th>
                    <th>Payment Status</th>
                    <th style="text-align: center;">Action</th>
                </tr>
            </thead>
            <tbody>
                @forelse($orders as $order)
                <tr>
                    <td style="font-weight: 700; color: #2563eb;">#{{ $order->order_number }}</td>
                    <td style="font-weight: 600;">{{ $order->user->name }}</td>
                    <td style="font-weight: 700;">{{ number_format($order->total_amount, 0) }} BDT</td>
                    
                    <form action="{{ route('admin.orders.update', $order->id) }}" method="POST" style="margin:0;">
                        @csrf

                        <td>
                            <select name="status" class="admin-control" style="width: 130px; padding: 5px 8px; font-size: 13px;">
                                <option value="pending" {{ $order->status == 'pending' ? 'selected' : '' }}>Pending</option>
                                <option value="processing" {{ $order->status == 'processing' ? 'selected' : '' }}>Processing</option>
                                <option value="shipped" {{ $order->status == 'shipped' ? 'selected' : '' }}>Shipped</option>
                                <option value="delivered" {{ $order->status == 'delivered' ? 'selected' : '' }}>Delivered</option>
                            </select>
                        </td>
                        <td>
                            <select name="payment_status" class="admin-control" style="width: 110px; padding: 5px 8px; font-size: 13px;">
                                <option value="unpaid" {{ $order->payment_status == 'unpaid' ? 'selected' : '' }}>Unpaid</option>
                                <option value="paid" {{ $order->payment_status == 'paid' ? 'selected' : '' }}>Paid</option>
                            </select>
                        </td>
                        <td>
                            <div style="display: flex; justify-content: center;">
                                <button type="submit" class="btn-admin btn-blue" style="padding: 6px 12px; font-size: 12px;">Update</button>
                            </div>
                        </td>
                    </form>
                    </tr>
                @empty
                <tr>
                    <td colspan="6" style="text-align: center; padding: 40px; color: #94a3b8; font-weight: 600;">
                        No customer orders placed yet.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if(method_exists($orders, 'links') && $orders->hasPages())
        <div class="admin-pagination">
            {{ $orders->links() }}
        </div>
    @endif

</div>
@endsection