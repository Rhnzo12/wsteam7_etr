<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;

class ProductManController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Product::query();

        if ($request->has('search') && $request->search != '') {
            $search = $request->search;
            $query->where('title', 'like', "%{$search}%")
                  ->orWhere('desc', 'like', "%{$search}%")
                  ->orWhere('size', 'like', "%{$search}%")
                  ->orWhere('price', 'like', "%{$search}%")
                  ->orWhere('stock', 'like', "%{$search}%")
                  ->orWhere('category_name', 'like', "%{$search}%")
                  ->orWhere('date_made', 'like', "%{$search}%");
        }

        $products = $query->get();

        return view('admin.product_manage', compact('products'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $categories = Category::all();
        return view('admin.add_product', compact('categories'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'desc' => 'required|string',
            'price' => 'required|numeric|min:0',
            'stock' => 'required|integer|min:0',
            'date_made' => 'nullable|date',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'size' => 'nullable|string|max:50',
            'category_id' => 'required|exists:categories,id',
        ]);

        // Fetch the category name
        $category = Category::find($request->category_id);

        Product::create([
            'title' => $request->title,
            'desc' => $request->desc,
            'price' => $request->price,
            'stock' => $request->stock,
            'date_made' => $request->date_made ?? now(),
            'image_path' => $request->file('image') 
                ? $request->file('image')->move('product/', $request->file('image')->getClientOriginalName())->getPathname()
                : null,
            'size' => $request->size,
            'category_id' => $request->category_id,
            'category_name' => $category ? $category->name : null,
        ]);

        return redirect()->route('admin.product_management')->with('success', 'Product created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        return view('admin.edit_product', [
            'product' => Product::findOrFail($id),
            'categories' => Category::all(),
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
         $request->validate([
        'title' => 'required|string|max:255',
        'desc' => 'required|string',
        'price' => 'required|numeric|min:0',
        'stock' => 'required|integer|min:0',
        'date_made' => 'nullable|date',
        'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        'size' => 'nullable|string|max:50',
        'category_id' => 'required|exists:categories,id',
    ]);

    $product = Product::findOrFail($id);

    // Handle image upload if present
    if ($request->hasFile('image')) {
        $image = $request->file('image');
        $image->move('product', $image->getClientOriginalName());
        $product->image_path = 'product/' . $image->getClientOriginalName();
    }

    // Fetch the category name
    $category = Category::find($request->category_id);

     Product::where('id', $id)->update([
        'title' => $request->title,
        'desc' => $request->desc,
        'price' => $request->price,
        'stock' => $request->stock,
        'date_made' => $request->date_made ?? now(),
        'image_path' => $product->image_path ?? null, 
        'size' => $request->size,
        'category_id' => $request->category_id,
        'category_name' => $category ? $category->name : null, 
    ]);
    return redirect()->route('admin.product_management')->with('success', 'Product updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $product = Product::findOrFail($id);

        // Optional lang para madelete yung image na nakasave sa public/product if exists
        if ($product->image_path && file_exists(public_path($product->image_path))) {
            unlink(public_path($product->image_path));
        }

        $product->delete();

        return redirect()->route('admin.product_management')->with('success', 'Product deleted successfully.');
    }
}
