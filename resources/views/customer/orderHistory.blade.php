@extends('customer.layouts.apps')

@section('content')
<div class="order-history">
    <h2>Order History</h2>

    {{-- Status Filter Navigation --}}
    <div class="order-filters">
        <a href="{{ route('customer.orderHistory') }}" class="{{ request('status') == null ? 'active' : '' }}">All</a>
        <a href="{{ route('customer.orderHistory', ['status' => 'to pay']) }}" class="{{ request('status') == 'to pay' ? 'active' : '' }}">To Pay</a>
        <a href="{{ route('customer.orderHistory', ['status' => 'to ship']) }}" class="{{ request('status') == 'to ship' ? 'active' : '' }}">To Ship</a>
        <a href="{{ route('customer.orderHistory', ['status' => 'to receive']) }}" class="{{ request('status') == 'to receive' ? 'active' : '' }}">To Receive</a>
        <a href="{{ route('customer.orderHistory', ['status' => 'delivered']) }}" class="{{ request('status') == 'delivered' ? 'active' : '' }}">Delivered</a>
    </div>

    <hr>

    @if($orders->isEmpty())
        <p>No orders found.</p>
    @else
        @foreach($orders as $order)
            <div class="order-box">
                <p><strong>OR Number:</strong> {{ $order->or_number }}</p>
                <p><strong>Product:</strong> {{ $order->prod_name }}</p>
                <p><strong>Quantity:</strong> {{ $order->qty }}</p>
                <p><strong>Price:</strong> ₱{{ number_format($order->price, 2) }}</p>
                <p><strong>Discount:</strong> {{ $order->discount }}%</p>
                <p><strong>Total:</strong> ₱{{ number_format($order->total, 2) }}</p>
                <p><strong>Status:</strong> {{ ucfirst($order->status) }}</p>
                <p><strong>Payment:</strong> {{ ucfirst($order->pay_mode) }} - {{ ucfirst($order->pay_status) }}</p>
                <hr>
            </div>
        @endforeach
    @endif
</div>

<style>
.order-history {
    max-width: 700px;
    margin: 20px auto;
    padding: 10px;
}

.order-filters {
    display: flex;
    gap: 10px;
    margin-bottom: 10px;
    flex-wrap: wrap;
}

.order-filters a {
    padding: 6px 12px;
    text-decoration: none;
    background: #eee;
    border-radius: 5px;
    color: #333;
}

.order-filters a.active {
    background: #00674F;
    color: #fff;
}

.order-box {
    background: #f9f9f9;
    border: 1px solid #ccc;
    padding: 15px;
    margin-bottom: 15px;
    border-radius: 8px;
}
</style>
@endsection
