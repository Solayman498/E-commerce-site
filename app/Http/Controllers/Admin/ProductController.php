<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{
    // ১. সব প্রোডাক্টের লিস্ট দেখানো
    public function index()
    {
        $products = Product::latest()->paginate(10);
        return view('admin.products.index', compact('products'));
    }

    // ২. নতুন প্রোডাক্ট তৈরির ফর্ম দেখানো
    public function create()
    {
        return view('admin.products.create');
    }

    // ৩. স্টোর মেথড (আপনি ইতিমধ্যে যা লিখেছেন, সাথে সামান্য রিফাইনমেন্ট)
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|max:255',
            'price' => 'required|numeric',
            'image' => 'required|image|mimes:jpeg,png,jpg,webp|max:2048',
            'category' => 'required',
            'stock' => 'required|integer',
        ]);

        $imageName = null;
        if ($request->hasFile('image')) {
            $imageName = time().'.'.$request->image->extension();
            $request->image->storeAs('public/products', $imageName);
        }

        Product::create([
            'name' => $request->name,
            'slug' => Str::slug($request->name),
            'price' => $request->price,
            'description' => $request->description,
            'category' => $request->category,
            'stock' => $request->stock,
            'image' => $imageName,
            'is_featured' => $request->has('is_featured'),
        ]);

        return redirect()->route('admin.products.index')->with('success', 'Product created successfully!');
    }

    // ৪. এডিট ফর্ম দেখানো
    public function edit($id)
    {
        $product = Product::findOrFail($id);
        return view('admin.products.edit', compact('product'));
    }

    // ৫. আপডেট মেথড
    public function update(Request $request, $id)
    {
        $product = Product::findOrFail($id);

        $request->validate([
            'name' => 'required|max:255',
            'price' => 'required|numeric',
            'category' => 'required',
            'stock' => 'required|integer',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
        ]);

        // নতুন ইমেজ আসলে পুরনো ইমেজ ডিলিট করা
        if ($request->hasFile('image')) {
            if ($product->image) {
                Storage::delete('public/products/' . $product->image);
            }
            $imageName = time().'.'.$request->image->extension();
            $request->image->storeAs('public/products', $imageName);
            $product->image = $imageName;
        }

        $product->update([
            'name' => $request->name,
            'slug' => Str::slug($request->name),
            'price' => $request->price,
            'description' => $request->description,
            'category' => $request->category,
            'stock' => $request->stock,
            'is_featured' => $request->has('is_featured'),
        ]);

        return redirect()->route('admin.products.index')->with('success', 'Product updated successfully!');
    }

    // ৬. ডিলিট মেথড
    public function destroy($id)
    {
        $product = Product::findOrFail($id);
        
        // ইমেজ ডিলিট করা
        if ($product->image) {
            Storage::delete('public/products/' . $product->image);
        }

        $product->delete();

        return redirect()->route('admin.products.index')->with('success', 'Product deleted successfully!');
    }
}