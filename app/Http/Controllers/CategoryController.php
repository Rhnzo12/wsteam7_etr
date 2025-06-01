<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Category::query();

        if ($request->has('search') && $request->search != '') {
            $search = $request->search;
            $query->where('name', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%")
                  ->orWhere('is_active', 'like', "%{$search}%");
        }

        $categories = $query->get();

        return view('admin.category_management', compact('categories'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $categories = Category::all();
        return view('admin.add_category', compact('categories'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'status' => 'required',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        // // Fetch the category name
        // $category = Category::find($request->category_id);

        Category::create([
            'name' => $request->name,
            'description' => $request->description,
            'status' => $request->status,
            'image' => $request->file('image') 
                ? $request->file('image')->move('category/', $request->file('image')->getClientOriginalName())->getPathname()
                : null,
        ]);

        return redirect()->route('admin.category_management')->with('success', 'Category created successfully.');
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
        $category = Category::findOrFail($id);
        return view('admin.edit_category', compact('category'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'status' => 'required',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
    ]);

    $category = Category::findOrFail($id);

    // Handle image upload if present
    if ($request->hasFile('image')) {
        $image = $request->file('image');
        $image->move('category', $image->getClientOriginalName());
        $category->image = 'category/' . $image->getClientOriginalName();
    }

    // // Fetch the category name
    // $category = Category::find($request->category_id);

     Category::where('id', $id)->update([
        'name' => $request->name,
        'description' => $request->description,
        'status' => $request->status,
        'image' => $category->image ?? null
    ]);
    return redirect()->route('admin.category_management')->with('success', 'Category updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $category = Category::findOrFail($id);

        // Check if the category has any related products
        if ($category->products()->exists()) {
            return redirect()->route('admin.category_management')->with('error', 'Cannot delete category because it has associated products.');
        }
        // Optional lang para madelete yung image na nakasave sa public/category if exists
        if ($category->image && file_exists(public_path($category->image))) {
            unlink(public_path($category->image));
        }

        $category->delete();

        return redirect()->route('admin.category_management')->with('success', 'Category deleted successfully.');
    }
}
