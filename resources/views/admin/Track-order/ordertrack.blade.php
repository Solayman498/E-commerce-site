@extends('layouts.admin')

@section('title', 'Manage Orders')

@section('admin_content')
<div class="container-fluid py-4">
    <div class="card shadow-sm border-0 rounded-lg">
        <div class="card-header bg-white py-3">
            <h5 class="mb-0 font-weight-bold text-gray-800">📦 Order Tracking Management</h5>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover align-middle border">
                    <thead class="bg-light">
                        <tr>
                            <th>Order #</th>
                            <th>Customer</th>
                            <th>Total Amount</th>
                            <th>Status</th>
                            <th>Payment Status</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($orders as $order)
                        <tr>
                            <td class="font-weight-bold text-primary">{{ $order->order_number }}</td>
                            <td>{{ $order->user->name }}</td>
                            <td>{{ number_format($order->total_amount, 0) }} BDT</td>
                            <form action="{{ route('admin.orders.update', $order->id) }}" method="POST">
                                @csrf
                                <td>
                                    <select name="status" class="form-control form-control-sm border-{{ $order->status == 'delivered' ? 'success' : 'info' }}">
                                        <option value="pending" {{ $order->status == 'pending' ? 'selected' : '' }}>Pending</option>
                                        <option value="processing" {{ $order->status == 'processing' ? 'selected' : '' }}>Processing</option>
                                        <option value="shipped" {{ $order->status == 'shipped' ? 'selected' : '' }}>Shipped</option>
                                        <option value="delivered" {{ $order->status == 'delivered' ? 'selected' : '' }}>Delivered</option>
                                    </select>
                                </td>
                                <td>
                                    <select name="payment_status" class="form-control form-control-sm {{ $order->payment_status == 'paid' ? 'text-success font-weight-bold' : '' }}">
                                        <option value="unpaid" {{ $order->payment_status == 'unpaid' ? 'selected' : '' }}>Unpaid</option>
                                        <option value="paid" {{ $order->payment_status == 'paid' ? 'selected' : '' }}>Paid</option>
                                    </select>
                                </td>
                                <td>
                                    <button type="submit" class="btn btn-primary btn-sm px-3 shadow-sm">
                                        Update
                                    </button>
                                </td>
                            </form>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection


