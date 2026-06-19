@extends('layouts.admin')

@section('admin_content')
<div class="admin-card" style="max-width: 800px;">
    
    <div class="admin-header-flex">
        <h2>Edit Product: {{ $product->name }}</h2>
    </div>

    <form action="{{ route('admin.products.update', $product->id) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')
        
        <div class="admin-form-group">
            <label>Product Name</label>
            <input type="text" name="name" class="admin-control" value="{{ $product->name }}" required>
        </div>

        <div class="admin-form-row">
            <div class="admin-form-group">
                <label>Price (BDT)</label>
                <input type="number" name="price" class="admin-control" value="{{ $product->price }}" required>
            </div>
            <div class="admin-form-group">
                <label>Category</label>
                <select name="category" class="admin-control">
                    <option value="Dog" {{ $product->category == 'Dog' ? 'selected' : '' }}>Dog</option>
                    <option value="Cat" {{ $product->category == 'Cat' ? 'selected' : '' }}>Cat</option>
                    <option value="Birds" {{ $product->category == 'Birds' ? 'selected' : '' }}>Birds</option>
                    <option value="Fish" {{ $product->category == 'Fish' ? 'selected' : '' }}>Fish</option>
                </select>
            </div>
        </div>

        <div class="admin-form-group">
            <label>Stock Quantity</label>
            <input type="number" name="stock" class="admin-control" value="{{ $product->stock }}">
        </div>

        <div class="admin-form-group">
            <label>Description</label>
            <textarea name="description" class="admin-control" rows="4">{{ $product->description }}</textarea>
        </div>

        <div class="admin-form-group">
            <label>Change Image (Leave blank to keep current)</label>
            <input type="file" name="image" class="admin-control">
            <img src="{{ asset('storage/products/' . $product->image) }}" alt="Product" class="admin-img-thumb" style="margin-top: 10px;" onerror="this.src='https://via.placeholder.com/50'">
        </div>

        <div class="admin-form-group">
            <label style="display: inline-flex; align-items: center; gap: 6px; cursor: pointer;">
                <input type="checkbox" name="is_featured" value="1" {{ $product->is_featured ? 'checked' : '' }} style="width:16px; height:16px;"> Mark as Featured
            </label>
        </div>

        <div class="admin-actions-gap" style="margin-top: 24px; border-top: 1px solid #e2e8f0; padding-top: 16px;">
            <button type="submit" class="btn-admin btn-amber">Update Product</button>
            <a href="{{ route('admin.products.index') }}" class="btn-admin btn-cancel">Cancel</a>
        </div>
    </form>

</div>
@endsection