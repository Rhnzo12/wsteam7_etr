@extends('layouts.app')

@section('content')
<h2>Order #{{ $order->id }}</h2>
<p>Status: {{ $order->status }}</p>
<p>Tracking Number: {{ $order->tracking_number }}</p>
<p>Shipping To: {{ $order->shipping_name }}, {{ $order->shipping_address }}, {{ $order->shipping_city }}, {{ $order->shipping_zip }}, {{ $order->shipping_country }}</p>

@foreach ($order->items as $item)
    <p>{{ $item->product->name }} — {{ $item->quantity }} x ${{ $item->price }}</p>
@endforeach

<p><strong>Total:</strong> ${{ $order->total_price }}</p>
@endsection
