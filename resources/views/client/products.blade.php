@extends('client.layouts.app')

@section('content')
    <section class="container">
        <h2 class="section-heading">Our Products</h2>

        {{-- Optional: Category Filter --}}
        <div class="category-filter" style="margin-bottom: 30px; text-align: center;">
            <a href="{{ route('clientProducts') }}"
                style="margin: 0 10px; text-decoration: none; color: #5b21b6; font-weight: bold;">All Categories</a>
            @foreach ($category as $cat)
                <a href="{{ route('clientCategoryProducts', Str::slug($cat->name)) }}"
                    style="margin: 0 10px; text-decoration: none; color: #333;">{{ $cat->name }}</a>
            @endforeach
        </div>

        @if ($products->count() > 0)
            <div class="product-grid">
                @foreach ($products as $item)
                    <div class="product-card">
                        {{-- Product Image --}}
                        <img src="{{ asset($item->image_path ?? 'images/default-product.png') }}" alt="{{ $item->title }}">

                        <h3><a href="{{ route('clientProductDetail', $item->id) }}">{{ $item->title }}</a></h3>
                        <p>₱{{ number_format($item->price, 2) }}</p>

                        {{-- Add to Cart button --}}
                        <button class="add-to-cart-btn" data-product-id="{{ $item->id }}"
                            data-product-stock="{{ $item->stock }}" data-product-title="{{ $item->title }}">
                            Add to Cart
                        </button>
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

    @push('scripts')
        {{-- jQuery for AJAX --}}
        <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

        {{-- SweetAlert2 --}}
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

        <script>
            $(document).ready(function() {
                $('.add-to-cart-btn').on('click', function() {
                    let productId = $(this).data('product-id');
                    let productStock = $(this).data('product-stock');
                    let productTitle = $(this).data('product-title');
                    let quantity = 1;

                    if (productStock <= 0) {
                        Swal.fire({
                            icon: 'warning',
                            title: 'Out of Stock',
                            text: productTitle + ' is out of stock!',
                        });
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
                                Swal.fire({
                                    icon: 'success',
                                    title: 'Added to Cart',
                                    text: productTitle + ' has been added to your cart!',
                                    timer: 2000,
                                    showConfirmButton: false
                                });
                                updateCartCount(response.cartCount);
                            } else if (response.status === 'failed' && response.code === 202) {
                                Swal.fire({
                                    icon: 'warning',
                                    title: 'Stock Limit',
                                    text: 'Failed to add ' + productTitle + '. Quantity exceeds available stock.',
                                });
                            } else {
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Error',
                                    text: 'An error occurred. Please try again.',
                                });
                            }
                        },
                        error: function(xhr) {
                            console.error("AJAX Error:", xhr.responseText);
                            Swal.fire({
                                icon: 'error',
                                title: 'Error',
                                text: 'Error adding to cart. Please check console for details.',
                            });
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
