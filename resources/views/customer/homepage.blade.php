@extends('layouts.app')

@section('content')
<h2>Laravel Shop</h2>

<form method="GET" action="/">
    <div class="row mb-3">
        <div class="col-md-4">
            <input name="search" type="text" class="form-control" placeholder="Search products..." value="{{ request('search') }}">
        </div>
        <div class="col-md-4">
            <select name="category" class="form-control">
                <option value="">All Categories</option>
                @foreach ($categories as $category)
                    <option value="{{ $category }}" {{ request('category') === $category ? 'selected' : '' }}>
                        {{ ucfirst($category) }}
                    </option>
                @endforeach
            </select>
        </div>
        <div class="col-md-4">
            <button class="btn btn-primary" type="submit">Filter</button>
        </div>
    </div>
</form>

@if($featured->count())
    <h4>Featured Deals</h4>
    <div class="row mb-4">
        @foreach($featured as $product)
            <div class="col-md-4">
                <div class="card bg-light">
                    <div class="card-body">
                        <strong>{{ $product->name }}</strong><br>
                        <span>${{ $product->price }}</span><br>
                        <a href="/products/{{ $product->id }}" class="btn btn-sm btn-success mt-2">View</a>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
@endif

<h4>All Products</h4>
<div class="row">
    @foreach($products as $product)
        <div class="col-md-4 mb-4">
            <div class="card">
                <div class="card-body">
                    <h5>{{ $product->name }}</h5>
                    <p>${{ $product->price }}</p>
                    <a href="/products/{{ $product->id }}" class="btn btn-primary">View Product</a>
                </div>
            </div>
        </div>
    @endforeach
</div>

{{ $products->links() }}
@endsection
