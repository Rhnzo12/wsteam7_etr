@extends('client.layouts.app')

@section('content')
    <section class="container">
        @if ($category) {{-- Check if the category exists --}}
            <h2 class="section-heading">Products in "{{ $category->name }}"</h2>

            @if ($products && $products->count() > 0)
                <div class="product-grid">
                    @foreach ($products as $item)
                        <div class="product-card">
                            <img src="{{ asset($item->image_path ?? 'images/default-product.png') }}" alt="{{ $item->title }}">
                            <h3>
                                <a href="{{ route('customerProductDetail', $item->id) }}">{{ $item->title }}</a>
                            </h3>
                            <p>₱{{ number_format($item->price, 2) }}</p>
                            <a href="{{ route('customerProductDetail', $item->id) }}" class="add-to-cart-btn"
                                style="display: inline-block; padding: 10px 15px; background-color: #00674F; color: white; border: none; border-radius: 5px; text-decoration: none;">
                                View Product
                            </a>
                        </div>
                    @endforeach
                </div>
            @else
                <p style="text-align: center;">No products found in this category.</p>
            @endif
        @else
            <p style="text-align: center; font-size: 1.2em; color: #888;">Category not found.</p>
        @endif
    </section>
@endsection
