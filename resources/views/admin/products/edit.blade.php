@extends('layouts.admin')

@section('admin_content')
<div class="card">
    <h2>Edit Product: {{ $product->name }}</h2>
    <form action="{{ route('admin.products.update', $product->id) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')
        
        <div class="form-group">
            <label>Product Name</label>
            <input type="text" name="name" class="form-control" value="{{ $product->name }}" required>
        </div>

        <div style="display: flex; gap: 20px;">
            <div class="form-group" style="flex: 1;">
                <label>Price (BDT)</label>
                <input type="number" name="price" class="form-control" value="{{ $product->price }}" required>
            </div>
            <div class="form-group" style="flex: 1;">
                <label>Category</label>
                <select name="category" class="form-control">
                    <option value="Dog" {{ $product->category == 'Dog' ? 'selected' : '' }}>Dog</option>
                    <option value="Cat" {{ $product->category == 'Cat' ? 'selected' : '' }}>Cat</option>
                    <option value="Birds" {{ $product->category == 'Birds' ? 'selected' : '' }}>Birds</option>
                </select>
            </div>
        </div>

        <div class="form-group">
            <label>Stock Quantity</label>
            <input type="number" name="stock" class="form-control" value="{{ $product->stock }}">
        </div>

        <div class="form-group">
            <label>Description</label>
            <textarea name="description" class="form-control" rows="4">{{ $product->description }}</textarea>
        </div>

        <div class="form-group">
            <label>Change Image (Leave blank to keep current)</label>
            <input type="file" name="image" class="form-control">
            <img src="{{ asset('storage/products/' . $product->image) }}" alt="Product Image" width="80" style="margin-top: 10px; border-radius: 5px;">
        </div>

        <div class="form-group">
            <label>
                <input type="checkbox" name="is_featured" value="1" {{ $product->is_featured ? 'checked' : '' }}> Mark as Featured
            </label>
        </div>

        <button type="submit" class="btn btn-edit">Update Product</button>
        <a href="{{ route('admin.products.index') }}" style="margin-left: 10px; color: #666;">Cancel</a>
    </form>
</div>
@endsection