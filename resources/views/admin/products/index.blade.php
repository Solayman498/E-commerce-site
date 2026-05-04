@extends('layouts.admin')

@section('admin_content')
<div class="card">
    <div style="display: flex; justify-content: space-between; align-items: center;">
        <h2>Product List</h2>
        <a href="{{ route('admin.products.create') }}" class="btn btn-add">+ Add New Product</a>
    </div>

    <table>
        <thead>
            <tr>
                <th>Image</th>
                <th>Name</th>
                <th>Category</th>
                <th>Price</th>
                <th>Stock</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach($products as $product)
            <tr>
                <td><img src="{{ asset('storage/products/' . $product->image) }}" alt="Product Image" width="50" style="border-radius: 5px;"></td>
                <td>{{ $product->name }}</td>
                <td>{{ $product->category }}</td>
                <td>{{ $product->price }} BDT</td>
                <td>{{ $product->stock }}</td>
                <td>
                    <a href="{{ route('admin.products.edit', $product->id) }}" class="btn btn-edit">Edit</a>
                    <form action="{{ route('admin.products.destroy', $product->id) }}" method="POST" style="display:inline;">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-delete" onclick="return confirm('Are you sure?')">Delete</button>
                    </form>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
    <div style="margin-top: 20px;">
        {{ $products->links() }}
    </div>
</div>
@endsection