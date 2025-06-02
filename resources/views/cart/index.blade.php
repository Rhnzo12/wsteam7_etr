@extends('layouts.app')

@section('content')
<h2>Your Cart</h2>

@forelse ($cartItems as $item)
    <p>{{ $item->product->name }} - {{ $item->quantity }} x ${{ $item->product->price }}</p>
@empty
    <p>Your cart is empty.</p>
@endforelse

<h4>Apply Coupon</h4>
<form method="POST" action="/coupon/apply">
    @csrf
    <input type="text" name="code" placeholder="Enter coupon code">
    <button type="submit">Apply</button>
</form>

<h4>Proceed to Checkout</h4>
<form method="POST" action="/checkout">
    @csrf
    <input type="text" name="shipping_name" placeholder="Full Name" required>
    <input type="text" name="shipping_address" placeholder="Address" required>
    <input type="text" name="shipping_city" placeholder="City" required>
    <input type="text" name="shipping_zip" placeholder="ZIP Code" required>
    <input type="text" name="shipping_country" placeholder="Country" required>
    <button type="submit">Checkout</button>
</form>
@endsection
