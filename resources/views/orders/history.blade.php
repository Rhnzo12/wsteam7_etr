@extends('layouts.app')

@section('content')
<h2>Your Order History</h2>

@forelse ($orders as $order)
    <div>
        <p><a href="{{ url('/order/' . $order->id) }}">Order #{{ $order->id }}</a></p>
        <p>Status: {{ $order->status }} | Total: ${{ $order->total_price }}</p>
    </div>
@empty
    <p>You have no past orders.</p>
@endforelse
@endsection
