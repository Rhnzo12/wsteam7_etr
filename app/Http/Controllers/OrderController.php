<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB; 

class OrderController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Order::query();

        if ($request->has('search') && $request->search != '') {
            $search = $request->search;
            $query->where('user_id', 'like', "%{$search}%")
                  ->orWhere('product_id', 'like', "%{$search}%")
                  ->orWhere('or_number', 'like', "%{$search}%")
                  ->orWhere('prod_name', 'like', "%{$search}%")
                  ->orWhere('name', 'like', "%{$search}%")
                  ->orWhere('address', 'like', "%{$search}%")
                  ->orWhere('number', 'like', "%{$search}%")
                  ->orWhere('price', 'like', "%{$search}%")
                  ->orWhere('total', 'like', "%{$search}%")
                  ->orWhere('pay_mode', 'like', "%{$search}%")
                  ->orWhere('pay_status', 'like', "%{$search}%")
                  ->orWhere('status', 'like', "%{$search}%");
        }

        $orders = $query->get();

        return view('admin.orders_management', compact('orders'));
    }

    public function updateStatus(Request $request, Order $order)
    {
        $request->validate([
            'status' => 'required|in:to pay,to ship,to recieve,delivered',
        ]);

        $order->status = $request->status;
        $order->save();

        return back()->with('success', 'Order status updated successfully.');
    }
public function store(Request $request)
{
    $request->validate([
        'name' => 'required|string',
        'address' => 'required|string',
        'number' => 'required|string',
        'pay_mode' => 'required|in:cash on delivery,online payment',
    ]);

    $user = session('users');

    // Extract user id safely
    if (is_array($user)) {
        $user_id = $user['id'] ?? null;
    } elseif (is_object($user)) {
        $user_id = $user->id ?? null;
    } else {
        $user_id = null;
    }

    if (!$user_id) {
        return redirect()->route('login')->with('error', 'Please log in to place an order.');
    }

    $cart = session('cart', []);
    if (empty($cart)) {
        return redirect()->back()->with('error', 'Your cart is empty.');
    }

    $orders = [];
    $lastOrNumber = Order::max('or_number');
    $newOrNumber = $lastOrNumber ? $lastOrNumber + 1 : 1001;

    foreach ($cart as $id => $item) {
        $qty = $item['quantity'];
        $price = $item['price'];

        $discount = 0;
        if ($request->pay_mode === 'online payment') {
            $discount = $qty >= 3 ? 0.12 : 0.10;
        }

        $orders[] = [
            'user_id' => $user_id,
            'product_id' => $id,
            'or_number' => $newOrNumber,
            'prod_name' => $item['title'],
            'price' => $price,
            'qty' => $qty,
            'discount' => $discount,  // store decimal fraction
            'name' => $request->name,
            'address' => $request->address,
            'number' => $request->number,
            'pay_mode' => $request->pay_mode,
            'pay_status' => $request->pay_mode === 'online payment' ? 'paid' : 'unpaid',
            'status' => $request->pay_mode === 'online payment' ? 'to ship' : 'to pay',
            'created_at' => now(),
            'updated_at' => now(),
        ];

        // Decrement stock quantity for product
        DB::table('products')->where('id', $id)->decrement('stock', $qty);
    }

    Order::insert($orders);

    session()->forget('cart');

    return redirect()->route('customer.orderHistory')->with('success', 'Order placed successfully.');
}


public function orderHistory(Request $request)
{
    $user = session('users');
    $user_id = is_array($user) ? ($user['id'] ?? null) : (is_object($user) ? $user->id ?? null : null);

    if (!$user_id) {
        return redirect()->route('login')->with('error', 'Please log in first.');
    }

    $query = Order::where('user_id', $user_id);

    if ($request->has('status')) {
        $query->where('status', $request->status);
    }

    $orders = $query->orderBy('created_at', 'desc')->get();

    return view('customer.orderHistory', compact('orders'));
}



    public function showCheckOut()
{
    // Get cart from session or empty array if null
    $cart = session('cart', []);

    // You can calculate total here if needed
    $total = 0;
    foreach ($cart as $item) {
        $total += $item['price'] * $item['quantity'];
    }

    // Pass cart and total to view
    return view('customer.customerCheckout', compact('cart', 'total'));
}




    public function create() {

    }

    public function show(Order $order) {

    }

    public function edit(Order $order) {

    }

    public function update(Request $request, Order $order) {

    }

    public function destroy(string $id)
    {
        $order = Order::findOrFail($id);
        $order->delete();
        return redirect()->route('admin.orders_management')->with('success', 'Order deleted successfully.');
    }
}
