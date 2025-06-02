<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Category;
use App\Models\Product;

class ProductController extends Controller
{
    //
    // ProductController.php
    public function index()
    {
        $categories = Category::all();
        $products = Product::with('category')->paginate(10); 
        return view('products.index', compact('products', 'categories'));
    }

    //Search product
    public function search(Request $request)
    {
        $query = Product::query();

        if ($request->filled('category')) {
            $query->where('category_id', $request->category);
        }

        if ($request->filled('search')) {
            $query->where('name', 'like', '%' . $request->search . '%');
        }

        $products = $query->with('category')->paginate(10);
        $categories = Category::all();

        return view('products.index', compact('products', 'categories'));
    }

    //Show product
    public function show($id)
        {
            $product = Product::with('category')->findOrFail($id);

            // Fetch all unique sizes for products with the same image and title
            $sizes = Product::where('image_path', $product->image_path)
                ->where('title', $product->title)
                ->pluck('size')
                ->unique()
                ->values();

            return view('products.show', compact('product', 'sizes'));
        }


}
