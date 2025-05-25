<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function adminDashboard()
    {
        if (!session()->has('users')) {
        return redirect('/login')->with('error', 'Please login first.');
    }
        return view('admin.admin_dashboard');
    }

    public function custDashboard()
    {
        if (!session()->has('users')) {
        return redirect('/login')->with('error', 'Please login first.');
    }
        return view('customer.dashboard');
    }
    public function loginUser(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|string',
        ]);
        $credentials = $request->only('email', 'password');
        $user = User::where('email', $credentials['email'])->first();

        if ($user && Hash::check($credentials['password'], $user->password)) {
            // Store user in session
            session(['users' => $user]);

            if ($user->role === 'admin') {
                return redirect('admin/admin_dashboard')->with('success', 'Welcome Admin!');
            } elseif ($user->role === 'customer') {
                return redirect('customer/dashboard')->with('success', 'Welcome Customer!');
            } else {
                return back()->with('error', 'Role not recognized.');
            }
        } else {
            return back()->with('error', 'Invalid credentials');
        }
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('login');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('register');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Validate the incoming request data
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'phone' => 'required|numeric|digits:10',
            'password' => 'required|string|min:6|confirmed',
        ]);

        // Create a new user
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'password' => Hash::make($request->password),
            'role' => 'customer',
        ]);

        // Redirect to login page
        return redirect('/login')->with('success', 'Registration successful! You can now log in.');
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
    public function destroy()
    {
        // Forget the user session
        session()->forget('users');

        // Optionally, flush the entire session if needed
        // session()->flush();

        // Redirect to the login page with a success message
        return redirect('/login')->with('success', 'Logout successful');
    }
}
