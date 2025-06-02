<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Str;

class UserController extends Controller
{
    
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $search = $request->input('search');

        $users = User::where('role', '!=', 'admin') // Exclude admin
            ->when($search, function ($query, $search) {
                return $query->where(function ($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%");
                });
            })
            ->get();

        return view('admin.users_management', compact('users'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $users = User::all();
        return view('admin.add_users', compact('users'));
    }

    /**
     * Store a newly created resource in storage.
     */
   public function store(Request $request)
    {
        $request->validate([
            'name'         => 'required|string|max:255',
            'email'        => 'required|email|unique:users,email',
            'phone'        => 'nullable|string|max:20',
            'image_users'  => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        $imagePath = null;

        if ($request->hasFile('image_users')) {
            $image = $request->file('image_users');
            $imageName = time() . '_' . $image->getClientOriginalName();
            $image->move(public_path('images'), $imageName);
            $imagePath = 'images/' . $imageName;  // Save relative path for DB
        }

        User::create([
            'name'         => $request->name,
            'email'        => $request->email,
            'phone'        => $request->phone,
            'image_users'  => $imagePath,
            'role'         => 'customer',
            'status'       => 'active',
            'password'     => bcrypt('password123'),  // Default password
        ]);

        return redirect()->route('admin.users_management')->with('success', 'User created successfully.');
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
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $user = User::findOrFail($id);

        // Prevent deleting admin users
        if ($user->role === 'admin') {
            return redirect()->back()->with('success', 'Cannot delete admin users.');
        }

        // Optionally delete user image file
        if ($user->image_users && file_exists(public_path('images/' . basename($user->image_users)))) {
            unlink(public_path('images/' . basename($user->image_users)));
        }

        $user->delete();

        return redirect()->route('admin.users_management')->with('success', 'User deleted successfully.');
    }
}
