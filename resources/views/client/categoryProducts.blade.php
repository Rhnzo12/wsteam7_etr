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
                            <h3><a href="{{ route('clientProductDetail', $item->id) }}">{{ $item->title }}</a>
                        </h3>
                            <p>₱{{ number_format($item->price, 2) }}</p>
                            <button class="add-to-cart-btn" data-product-id="{{ $item->id }}"
                                data-product-stock="{{ $item->stock }}" data-product-title="{{ $item->title }}">Add to Cart</button>
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

    @push('scripts')
        <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
        <script>
            $(document).ready(function() {
                $('.add-to-cart-btn').on('click', function() {
                    let productId = $(this).data('product-id');
                    let productStock = $(this).data('product-stock');
                    let productTitle = $(this).data('product-title');
                    let quantity = 1; // Default quantity for adding from product listing

                    if (productStock <= 0) {
                        alert(productTitle + ' is out of stock!');
                        return;
                    }

                    $.ajax({
                        url: "{{ route('clientAddToCart') }}",
                        method: "POST",
                        data: {
                            _token: "{{ csrf_token() }}",
                            product_id: productId,
                            quantity: quantity
                        },
                        success: function(response) {
                            if (response.status === 'success') {
                                alert(productTitle + ' added to cart!');
                                updateCartCount(response.cartCount);
                            } else if (response.status === 'failed' && response.code === 202) {
                                alert('Failed to add ' + productTitle +
                                    ' to cart. Requested quantity exceeds stock.');
                            } else {
                                alert('An error occurred. Please try again.');
                            }
                        },
                        error: function(xhr) {
                            console.error("AJAX Error:", xhr.responseText);
                            alert('Error adding to cart. Please check console for details.');
                        }
                    });
                });

                function updateCartCount(count) {
                    $('#cart-count').text(count);
                }
            });
        </script>
    @endpush
@endsection
