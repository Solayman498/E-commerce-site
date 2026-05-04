<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Models\Product;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        
        $query = Product::select('id', 'name', 'price', 'image', 'category');

       
        if ($request->filled('category')) {
            $query->where('category', $request->category);
        }

        $products = $query->latest()->paginate(12); 

        return view('products', compact('products'));
    }
}
