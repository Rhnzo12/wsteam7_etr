@extends('layouts.template')

@section('title', 'Orders Management')

@section('content')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<link rel="stylesheet" href="{{ asset('css/admin_orders.css') }}">
<div class="order-container">
    <div class="order-header">
    <h2>Orders Management</h2>
        
    </div>

    <!-- Search Form -->
     <div class="alert-error" style="display: none; color: red;" id="error-search"></div>
    <form method="GET" action="{{ url()->current() }}" class="search-form">
        <input type="text" name="search" placeholder="Search orders..." value="{{ request('search') }}" id="search-input">
        <button type="submit">
            <i class="fa fa-search"></i> Search
        </button>
    </form>

    @if(session('success'))
        <div class="alert-success" id="success">{{ session('success') }}</div>
    @else  
        <div class="alert-success" style="display: none;" id="success"></div>
    @endif
<div class="table-responsive">
    <table class="order-table">
        <thead>
            <tr>
                <th>ID</th>
                <th>User ID</th>
                <th>OR Number</th>
                <th>Product Name</th>
                <th>Price</th>
                <th>Name</th>
                <th>Address</th>
                <th>Number</th>
                <th>Quantity</th>
                <th>Total</th>
                <th>Payment Mode</th>
                <th>Payment Status</th>
                <th>Status</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
        @forelse($orders as $order)
            <tr>
                <td>{{ $loop->iteration }}</td>
                <td>{{ $order->user_id }}</td>
                <td>{{ $order->or_number }}</td>
                <td>{{ $order->prod_name }}</td>
                <td>{{ $order->price }}</td>
                <td>{{ $order->name }}</td>
                <td>{{ $order->address }}</td>
                <td>{{ $order->number }}</td>
                <td>{{ $order->qty }}</td>
                <td>{{ $order->total }}</td>
                <td>{{ $order->pay_mode }}</td>
                <td>{{ $order->pay_status }}</td>
                <td>
                    <form action="{{ route('orders.updateStatus', $order->id) }}" method="POST" class="status-form">
                        @csrf
                        @method('PATCH')
                        <select name="status" class="status-select" onchange="this.form.submit()">
                            <option value="to pay" {{ $order->status == 'to pay' ? 'selected' : '' }}>To pay</option>
                            <option value="to ship" {{ $order->status == 'to ship' ? 'selected' : '' }}>To ship</option>
                            <option value="to recieve" {{ $order->status == 'to recieve' ? 'selected' : '' }}>To recieve</option>
                            <option value="delivered" {{ $order->status == 'delivered' ? 'selected' : '' }}>Delivered</option>
                            </select>
                        </form>
                    </td>
                <td>
                    <form action="{{ route('admin.orders_destroy', $order->id) }}" method="POST" style="display:inline;" class="delete-form">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn-action btn-delete">
                            <i class="fa fa-trash"></i> Delete
                        </button>
                    </form>
                </td>
            </tr>
        @empty
            <tr><td colspan="9" style="text-align:center;">No orders found.</td></tr>
        @endforelse
        </tbody>
    </table>
</div>
</div>
<script>
    $(document).ready(function() {
        $('.search-form').submit(function(e) {
            if(!$('#search-input').val()){
                $('#error-search').show().text('Search input cannot be empty').fadeOut(3000);
                e.preventDefault();
            }
        });
        $('.delete-form').submit(function(e) {
            e.preventDefault();
            const form = this;
            Swal.fire({
                title: 'Delete this orders?',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Yes, delete'
            }).then(result => {
                if (result.isConfirmed) form.submit();
            });
        });
        $('#success').fadeOut(3000);

    });
</script>
@endsection