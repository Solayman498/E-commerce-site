<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;

class HomeController extends Controller
{
    public function index()
    {
        // Get featured products for the homepage
        $featuredProducts = Product::where('is_featured', true)->take(8)->get();

        return view('home', compact('featuredProducts'));
    }
}
