<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Models\Product;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $query = Product::query();

        // category filter
        if ($request->has('category')) {
            $query->where('category', $request->category);
        }

        $products = $query->get();

        return view('products', compact('products'));
    }
}
