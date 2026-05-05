<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;

class HomeController extends Controller
{
    public function index()
    {
        
        $featuredProducts = Product::select('id', 'name', 'slug', 'price', 'image', 'category', 'is_featured')
            ->where('is_featured', true)
            ->latest() 
            ->take(8)
            ->get();

        return view('home', compact('featuredProducts'));
    }
}