@extends('layouts.admin')

@section('admin_content')
<div class="admin-card" style="max-width: 800px;">
    
    <div class="admin-header-flex">
        <h2>Add New Product</h2>
    </div>

    <form action="{{ route('admin.products.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        
        <div class="admin-form-group">
            <label>Product Name</label>
            <input type="text" name="name" class="admin-control" required>
        </div>
        
        <div class="admin-form-row">
            <div class="admin-form-group">
                <label>Price (BDT)</label>
                <input type="number" name="price" class="admin-control" required>
            </div>
            <div class="admin-form-group">
                <label>Category</label>
                <select name="category" class="admin-control" required>
                    <option value="Dog">Dog</option>
                    <option value="Cat">Cat</option>
                    <option value="Birds">Birds</option>
                    <option value="Fish">Fish</option>
                </select>
            </div>
        </div>

        <div class="admin-form-group">
            <label>Stock Quantity</label>
            <input type="number" name="stock" class="admin-control" required>
        </div>

        <div class="admin-form-group">
            <label>Description</label>
            <textarea name="description" class="admin-control" rows="4"></textarea>
        </div>

        <div class="admin-form-group">
            <label>Product Image</label>
            <input type="file" name="image" class="admin-control" required>
        </div>

        <div class="admin-form-group">
            <label style="display: inline-flex; align-items: center; gap: 6px; cursor: pointer;">
                <input type="checkbox" name="is_featured" value="1" style="width:16px; height:16px;"> Mark as Featured
            </label>
        </div>

        <div class="admin-actions-gap" style="margin-top: 24px; border-top: 1px solid #e2e8f0; padding-top: 16px;">
            <button type="submit" class="btn-admin btn-green">Save Product</button>
            <a href="{{ route('admin.products.index') }}" class="btn-admin btn-cancel">Cancel</a>
        </div>
    </form>

</div>
@endsection