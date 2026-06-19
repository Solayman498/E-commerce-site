@extends('layouts.admin')

@section('admin_content')
<div class="admin-card">
    
    <div class="admin-header-flex">
        <h2>Product List</h2>
        <a href="{{ route('admin.products.create') }}" class="btn-admin btn-green">+ Add New Product</a>
    </div>

    <div class="admin-table-wrapper">
        <table class="admin-table">
            <thead>
                <tr>
                    <th>Image</th>
                    <th>Name</th>
                    <th>Category</th>
                    <th>Price</th>
                    <th>Stock</th>
                    <th style="text-align: center;">Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($products as $product)
                <tr>
                    <td>
                        <img src="{{ asset('storage/products/' . $product->image) }}" alt="Product" class="admin-img-thumb" onerror="this.src='https://via.placeholder.com/50'">
                    </td>
                    <td style="font-weight: 600;">{{ $product->name }}</td>
                    <td>{{ $product->category }}</td>
                    <td>{{ number_format($product->price, 0) }} BDT</td>
                    <td>{{ $product->stock }}</td>
                    <td>
                        <div class="admin-actions-gap" style="justify-content: center;">
                            <a href="{{ route('admin.products.edit', $product->id) }}" class="btn-admin btn-amber">Edit</a>
                            
                            <form action="{{ route('admin.products.destroy', $product->id) }}" method="POST" style="display:inline; margin:0;" onsubmit="return confirm('Are you sure?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn-admin btn-rose">Delete</button>
                            </form>
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    @if($products->hasPages())
        <div class="admin-pagination">
            {{ $products->links() }}
        </div>
    @endif

</div>
@endsection