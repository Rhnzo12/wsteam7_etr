<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use App\Models\Shop;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class CustomerController extends Controller
{
     /**
     * Ito ang home page ng website.
     * Dito makikita ang impormasyon ng shop at mga bagong kategorya/produkto.
     */
    public function index()
        {
            $shop = Shop::first();
            $category = Category::all();
            $products = Product::latest()->take(8)->get(); // Or paginate()

            return view('customer.dashboard', [
                'shop' => $shop,
                'category' => $category,
                'products' => $products,
            ]);
        }

    /**
     * Ipinapakita nito ang lahat ng produkto na available.
     * May pagination din para hindi masyadong marami ang nakikita sa isang page.
     */
    public function products(){
        $data = [
            'shop' => Shop::first(), // Kukunin ang impormasyon ng shop
            'products' => Product::orderBy('id', 'DESC')->paginate(16), // Kukunin ang mga produkto, 16 per page
            'category' => Category::all()->sortByDesc('id'), // Kukunin ang lahat ng kategorya
            'title' => 'Products' // Ang titulo ng page
        ];

        return view('customer.products', $data); // Ibabalik ang 'products' view
    }

    /**
     * Naghahanap ito ng produkto batay sa keyword na inilagay ng user.
     * Ipapakita ang mga resultang tugma sa search term.
     */
    public function searchProduct(Request $request){
        $validator = Validator::make($request->all(), [ // Susuriin ang input ng user
            'product' => 'required|string|max:255' // Kailangan may input at hindi masyadong mahaba
        ]);

        if($validator->fails()){
            return redirect()->back()->withErrors($validator)->withInput(); // Kung may mali sa input, ibalik sa dating page na may error
        }else{
            $searchTerm = $request->product; // Ang hinahanap na term
            $data = [
                'title' => 'Search Results for ' . $searchTerm, // Titulo ng search results
                'shop' => Shop::first(), // Impormasyon ng shop
                'product' => Product::where('title', 'LIKE', '%'.$searchTerm.'%')->orderBy('id', 'DESC')->paginate(20), // Hanapin ang mga produkto na may tugmang title
                'search' => $searchTerm // Ang search term para sa view
            ];

            return view('customer.productSearch', $data); // Ibabalik ang 'productSearch' view
        }
    }

    /**
     * Ipinapakita nito ang listahan ng lahat ng kategorya ng produkto.
     */
    public function category()
    {
        $shop = Shop::first();
        $categories = Category::orderByDesc('id')->paginate(12);
        $products = Product::latest()->take(8)->get();

        return view('customer.category', [
            'shop' => $shop,
            'category' => $categories,
            'products' => $products,
        ]);
    }

    /**
     * Ipinapakita nito ang lahat ng produkto sa ilalim ng isang partikular na kategorya.
     */
    public function categoryProducts($id)
        {
            $shop = Shop::first();
            $category = Category::with('products')->findOrFail($id);

            // Assign products to a variable, not null
            $products = $category->products;

            return view('customer.categoryProducts', [
                'shop' => $shop,
                'category' => $category,
                'products' => $products,  // pass products explicitly
            ]);
        }


    /**
     * Ipinapakita nito ang detalyadong impormasyon ng isang produkto.
     * Mayroon din itong rekomendasyon ng ibang produkto.
     */
    public function productDetail($id) {
    $product = Product::findOrFail($id); // Find product by ID or fail

    // Fetch all sizes for products with the same image_path
    $sizes = Product::where('image_path', $product->image_path)
        ->pluck('size')         // Get the size column
        ->unique()              // Remove duplicates
        ->filter()              // Remove null or empty sizes
        ->values();             // Re-index the collection

    // Recommendations (same logic)
    if ($product->category && $product->category->products->count() > 1) {
        $recomendationProducts = $product->category->products
            ->where('id', '!=', $product->id)
            ->take(8);
    } else {
        $recomendationProducts = Product::where('id', '!=', $product->id)
            ->orderByDesc('id')
            ->take(8)
            ->get();
    }

    $data = [
        'shop' => Shop::first(),
        'product' => $product,
        'sizes' => $sizes,  // Pass sizes to view
        'recomendationProducts' => $recomendationProducts,
        'title' => Str::title(str_replace('-', ' ', $product->title))
    ];

    return view('customer.productDetail', $data);
}



    /**
     * Ipinapakita nito ang "About Us" page ng website.
     */
    public function about(){
        $data = [
            'shop' => Shop::first(), // Kukunin ang impormasyon ng shop
            'title' => 'About' // Ang titulo ng page
        ];

        return view('customer.about', $data); // Ibabalik ang 'about' view
    }
}
