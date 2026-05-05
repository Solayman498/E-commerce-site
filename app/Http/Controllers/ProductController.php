<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Models\Product;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        
        $query = Product::select('id', 'name', 'slug', 'price', 'image', 'category');

       
        if ($request->filled('category')) {
            $query->where('category', $request->category);
        }

        $products = $query->latest()->paginate(12); 

        return view('products', compact('products'));
    }

    public function show($slug)
    {
        //find product by slug from database
        $product = \App\Models\Product::where('slug', $slug)->firstOrFail();

        //  (Related Products)
        $relatedProducts = \App\Models\Product::where('category', $product->category)
                            ->where('id', '!=', $product->id)
                            ->take(4)
                            ->get();

        return view('products.show', compact('product', 'relatedProducts'));
    }
}
