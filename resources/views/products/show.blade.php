@extends('layouts.app') 

@section('title', $product->name . ' - PetShop')

@section('content')
<div class="py-12 bg-gray-50">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white shadow-xl rounded-2xl overflow-hidden p-8">
            
            <div class="flex flex-wrap -mx-4">
                <!-- Product Image Section -->
                <div class="w-full md:w-1/2 px-4 mb-8">
                    <!-- সরাসরি ইনলাইন স্টাইল দিয়ে ইমেজ সাইজ কন্ট্রোল করা হয়েছে যেন style.css এর ওপর নির্ভর না করতে হয় -->
                    <div class="shadow-sm border border-gray-100 rounded-2xl bg-white flex items-center justify-center overflow-hidden" style="height: 450px;">
                        <img src="{{ asset('storage/products/' . $product->image) }}" 
                             alt="{{ $product->name }}" 
                             style="max-height: 100%; max-width: 100%; object-fit: contain; padding: 20px;"
                             class="hover:scale-105 transition-transform duration-300">
                    </div>
                </div>

                <!-- Product Info Section -->
                <div class="w-full md:w-1/2 px-4">
                    <div class="md:pl-8">
                        <nav class="mb-4">
                            <span class="text-xs uppercase tracking-widest text-blue-600 font-bold bg-blue-50 px-3 py-1 rounded-full">
                                {{ $product->category }}
                            </span>
                        </nav>

                        <h1 class="text-4xl font-extrabold text-gray-900 mb-4">{{ $product->name }}</h1>
                        
                        <div class="flex items-center mb-6">
                            <span class="text-3xl font-bold text-gray-900">{{ number_format($product->price, 0) }}</span>
                            <span class="text-lg font-semibold text-gray-500 ml-2">BDT</span>
                        </div>

                        <div class="mb-8">
                            <h3 class="text-sm font-bold uppercase text-gray-400 tracking-wider mb-3 border-b pb-2">Description</h3>
                            <p class="text-gray-600 leading-relaxed text-lg italic">
                                {{ $product->description ?? 'No description available for this product.' }}
                            </p>
                        </div>

                        <div class="border-t border-gray-100 pt-6 mb-8">
                            <div class="flex items-center gap-4">
                                <div class="flex-1">
                                    <button class="w-full bg-blue-600 hover:bg-blue-700 text-white font-bold py-4 px-8 rounded-xl shadow-lg shadow-blue-200 transition-all active:scale-95">
                                        Add To Cart 🛒
                                    </button>
                                </div>
                                <div class="text-right">
                                    <p class="text-xs text-gray-400 uppercase font-bold">Availability</p>
                                    <p class="font-bold {{ $product->stock > 0 ? 'text-green-500' : 'text-red-500' }}">
                                        {{ $product->stock > 0 ? 'In Stock (' . $product->stock . ')' : 'Out of Stock' }}
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Related Products Section -->
            @if($relatedProducts->count() > 0)
            <div class="mt-16 pt-12 border-t border-gray-100">
                <h2 class="text-2xl font-bold text-gray-800 mb-8 flex items-center">
                    <span class="w-2 h-8 bg-blue-600 rounded-full mr-3"></span>
                    You May Also Like
                </h2>
                <div class="grid grid-cols-2 md:grid-cols-4 gap-6">
                    @foreach($relatedProducts as $related)
                        <div class="group border border-gray-100 rounded-xl p-4 hover:shadow-xl transition-all duration-300 bg-white">
                            <a href="{{ route('products.show', $related->slug) }}">
                                <div class="h-40 w-full mb-4 overflow-hidden rounded-lg">
                                    <img src="{{ asset('storage/products/' . $related->image) }}" 
                                         style="height: 100%; width: 100%; object-fit: contain;"
                                         class="group-hover:scale-110 transition-transform duration-300">
                                </div>
                                <h4 class="font-bold text-gray-800 truncate mb-1">{{ $related->name }}</h4>
                                <p class="text-blue-600 font-bold text-sm">{{ number_format($related->price, 0) }} BDT</p>
                            </a>
                        </div>
                    @endforeach
                </div>
            </div>
            @endif

        </div>
    </div>
</div>
@endsection