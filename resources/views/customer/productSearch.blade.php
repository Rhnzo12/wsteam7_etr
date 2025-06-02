@extends('customer.layouts.apps')

@section('content')
    <section class="container">
        <h2 class="section-heading">Search Results for "{{ $search ?? '' }}"</h2>

        @if (isset($errors) && $errors->any())
            <div style="color: red; text-align: center; margin-bottom: 20px;">
                @foreach ($errors->all() as $error)
                    <p>{{ $error }}</p>
                @endforeach
            </div>
        @endif

        @if ($product->count() > 0)
            <div class="product-grid">
                @foreach ($product as $item)
                    <div class="product-card">
                        <img src="{{ asset($item->image_path ?? 'images/default-product.png') }}" alt="{{ $item->title }}">
                        <h3>
                            <a href="{{ route('customerProductDetail', $item->id) }}">{{ $item->title }}</a>
                        </h3>
                        <p>₱{{ number_format($item->price, 2) }}</p>
                        <a href="{{ route('customerProductDetail', $item->id) }}"
                           class="add-to-cart-btn"
                           style="display: inline-block; padding: 10px 15px; background-color: #00674F; color: white; border: none; border-radius: 5px; text-decoration: none;">
                            View Product
                        </a>
                    </div>
                @endforeach
            </div>

            <div class="pagination" style="margin-top: 40px; text-align: center;">
                {{ $product->links() }}
            </div>
        @else
            <p style="text-align: center;">No products found.</p>
        @endif
    </section>
@endsection
