@extends('customer.layouts.apps')

@section('content')
    <section class="container">
        <h2 class="section-heading">Our Products</h2>

        {{-- Optional: Category Filter --}}
        <div class="category-filter" style="margin-bottom: 30px; text-align: center;">
            <a href="{{ route('customerProducts') }}"
                style="margin: 0 10px; text-decoration: none; color: #00674F; font-weight: bold;">All Categories</a>
            @foreach ($category as $cat)
                <a href="{{ route('customerCategoryProducts', $cat->id) }}"
                    style="margin: 0 10px; text-decoration: none; color: #333;">{{ $cat->name }}</a>
            @endforeach
        </div>

        @if ($products->count() > 0)
            <div class="product-grid">
                @foreach ($products as $item)
                    <div class="product-card">
                        {{-- Product Image --}}
                        <img src="{{ asset($item->image_path ?? 'images/default-product.png') }}" alt="{{ $item->title }}">

                        <h3><a href="{{ route('customerProductDetail', $item->id) }}">{{ $item->title }}</a></h3>
                        <p>₱{{ number_format($item->price, 2) }}</p>

                        {{-- View Product button (redirect to product detail page) --}}
                        <a href="{{ route('customerProductDetail', $item->id) }}" class="add-to-cart-btn"
                            style="display: inline-block; padding: 10px 15px; background-color: #00674F; color: white; border: none; border-radius: 5px; text-decoration: none;">
                            View Product
                        </a>
                    </div>
                @endforeach
            </div>

            {{-- Pagination --}}
            <div class="pagination" style="margin-top: 40px; text-align: center;">
                {{ $products->links() }}
            </div>
        @else
            <p style="text-align: center;">No products available at the moment.</p>
        @endif
    </section>
@endsection
