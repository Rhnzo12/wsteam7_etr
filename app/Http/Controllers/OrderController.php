<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use App\Models\Cart;
use App\Models\OrderItem;
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


    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(Order $order)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Order $order)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Order $order)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
         $order = Order::findOrFail($id);

        $order->delete();

        return redirect()->route('admin.orders_management')->with('success', 'Order deleted successfully.');
    }

    public function checkoutC(Request $request)
    {
        $request->validate([
            'shipping_name'    => 'required|string',
            'shipping_address' => 'required|string',
            'shipping_city'    => 'required|string',
            'shipping_zip'     => 'required|string',
            'shipping_country' => 'required|string',
        ]);

        $user = Auth::user();
        $cartItems = Cart::with('product')->where('user_id', $user->id)->get();

        if ($cartItems->isEmpty()) {
            return back()->withErrors(['Your cart is empty.']);
        }

        $total = 0;
        foreach ($cartItems as $item) {
            $total += $item->product->price * $item->quantity;
        }

        // Apply coupon if available
        if ($coupon = session('coupon')) {
            if ($coupon->discount_type === 'percent') {
                $total -= $total * ($coupon->discount_amount / 100);
            } else {
                $total -= $coupon->discount_amount;
            }
        }

        $order = Order::create([
            'user_id'           => $user->id,
            'total_price'       => $total,
            'tracking_number'   => strtoupper(Str::random(10)),
            'shipping_name'     => $request->shipping_name,
            'shipping_address'  => $request->shipping_address,
            'shipping_city'     => $request->shipping_city,
            'shipping_zip'      => $request->shipping_zip,
            'shipping_country'  => $request->shipping_country,
        ]);

        foreach ($cartItems as $item) {
            OrderItem::create([
                'order_id'   => $order->id,
                'product_id' => $item->product->id,
                'quantity'   => $item->quantity,
                'price'      => $item->product->price,
            ]);
        }

        // Clear cart and session
        Cart::where('user_id', $user->id)->delete();
        session()->forget('coupon');

        return redirect()->route('order.show', $order->id)
                         ->with('success', 'Order placed successfully!');
    }

    public function showC($id)
    {
        $order = Order::with('items.product')->where('id', $id)->where('user_id', Auth::id())->firstOrFail();
        return view('orders.show', compact('order'));
    }

    public function historyC()
    {
        $orders = Order::with('items.product')->where('user_id', Auth::id())->latest()->get();
        return view('orders.history', compact('orders'));
    }
}
