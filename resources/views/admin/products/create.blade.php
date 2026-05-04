@extends('layouts.admin')

@section('admin_content')
<div class="card">
    <h2>Add New Product</h2>
    <form action="{{ route('admin.products.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="form-group">
            <label>Product Name</label>
            <input type="text" name="name" class="form-control" required>
        </div>
        
        <div style="display: flex; gap: 20px;">
            <div class="form-group" style="flex: 1;">
                <label>Price (BDT)</label>
                <input type="number" name="price" class="form-control" required>
            </div>
            <div class="form-group" style="flex: 1;">
                <label>Category</label>
                <select name="category" class="form-control" required>
                    <option value="Dog">Dog</option>
                    <option value="Cat">Cat</option>
                    <option value="Birds">Birds</option>
                    <option value="Fish">Fish</option>
                </select>
            </div>
        </div>

        <div class="form-group">
            <label>Stock Quantity</label>
            <input type="number" name="stock" class="form-control" required>
        </div>

        <div class="form-group">
            <label>Description</label>
            <textarea name="description" class="form-control" rows="4"></textarea>
        </div>

        <div class="form-group">
            <label>Product Image</label>
            <input type="file" name="image" class="form-control" required>
        </div>

        <div class="form-group">
            <label>
                <input type="checkbox" name="is_featured" value="1"> Mark as Featured
            </label>
        </div>

        <button type="submit" class="btn btn-add">Save Product</button>
        <a href="{{ route('admin.products.index') }}" style="margin-left: 10px; color: #666;">Cancel</a>
    </form>
</div>
@endsection