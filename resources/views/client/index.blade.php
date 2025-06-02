@extends('client.layouts.app') {{-- Ito ang nag-e-extend ng master layout --}}
<style>
    .category-img {
    width: 100%;
    height: 200px;
    object-fit: cover;
    border-radius: 10px;
    }
    .category-card {
        text-align: center;
        padding: 15px;
    }
    .product-card {
        text-align: center;
        padding: 15px;
    }
    .product-img {
    width: 100%;
    height: 200px;
    object-fit: cover;
    border-radius: 10px;
    }

</style>

@section('content')
    {{-- Dito ilalagay ang content na papasok sa @yield('content') sa master layout --}}

    {{-- Hero Section --}}
     <section class="hero-section">
       <h1>Crafting Comfort for 75 Years.</h1>
       <h3 style="color:#9B7514;">THREAD OF THE FILIPINO.</h3>
        <p>Experience fashion that feels good and does good—crafted with care, made to last, and designed to fit your lifestyle effortlessly.</p>
        <a href="{{ route('clientProducts') }}" class="btn-more">Shop Now <span class="arrow">&rarr;</span></a>
    </section>

    {{-- "Why Choose Us" Section --}}
    <section class="container">
        <h2 class="section-heading">Why Choose Us</h2>
        <div style="text-align: center; padding: 30px;">
            <p>At Crispa Clothing, we’re all about combining quality, comfort, and Filipino pride. Each piece is thoughtfully made using premium materials to ensure it looks great and feels even better. As a proudly local brand, we aim to highlight Filipino craftsmanship while supporting our communities. Our designs are timeless—created to keep you looking stylish no matter the season. We believe fashion should be affordable without compromising on quality. Along with excellent customer care and fast, dependable shipping across the Philippines, we make your shopping experience as smooth and enjoyable as possible.</p>
        </div>
    </section>

    {{-- Products Section Heading (New addition) --}}
     <section class="container">
        <h2 class="section-heading">Our Products</h2>
        <div class="product-grid">
            @forelse($products as $prod)
                <div class="product-card">
                    <h3>
                        <img class="product-img"  src="{{ asset($prod->image_path ?? 'images/default-product.png') }}" alt="{{ $prod->title }}">

                        <a href="{{ route('clientProductDetail', $prod->id) }}">{{ $prod->title }}</a>

                    </h3>
                </div>
            @empty
                <p style="text-align: center; grid-column: 1 / -1;">No categories found.</p>
            @endforelse
            </div>
        <a href="{{ route('clientProducts') }}" class="btn-more">View All Products <span class="arrow">&rarr;</span></a>
    </section>

    {{-- Categories Section --}}
    <section class="container">
        <h2 class="section-heading">Categories</h2>
        <div class="category-grid">
            @forelse($category as $cat)
                <div class="category-card">
                    <h3>
                        <img class="category-img"  src="{{ asset($cat->image ?? 'images/default-product.png') }}" alt="{{ $cat->name }}">

                         <a href="{{ route('clientProductDetail', $cat->id) }}">{{ $cat->name }}</a>

                    </h3>
                </div>
            @empty
                <p style="text-align: center; grid-column: 1 / -1;">No categories found.</p>
            @endforelse
        </div>
        <a href="{{ route('clientCategory') }}" class="btn-more">More Categories <span class="arrow">&rarr;</span></a>
    </section>
@endsection
