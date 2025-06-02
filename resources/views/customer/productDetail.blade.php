@extends('customer.layouts.apps')

@section('content')
    <section class="container" style="padding: 40px 0;">
        @if ($product)
            <div class="product-detail-container" style="display: flex; flex-wrap: wrap; gap: 40px; justify-content: center;">
                {{-- Product Image --}}
                <div class="product-images" style="flex: 1; min-width: 300px; max-width: 500px; text-align: center;">
                    @if (!empty($product->image_path))
                        <img id="main-product-image" src="{{ asset($product->image_path) }}"
                             alt="{{ $product->title }}"
                             style="max-width: 100%; height: auto; max-height: 450px; object-fit: contain; border-radius: 8px; box-shadow: 0 2px 5px rgba(0,0,0,0.1);">
                    @else
                        <img src="{{ asset('images/default-product.png') }}" alt="Default Product Image"
                             style="max-width: 100%; height: auto; max-height: 450px; object-fit: contain; border-radius: 8px; box-shadow: 0 2px 5px rgba(0,0,0,0.1);">
                    @endif
                </div>

                {{-- Product Details --}}
                <div class="product-info" style="flex: 1; min-width: 300px; max-width: 600px;">
                    <h1 style="font-size: 2.5em; color: #333; margin-bottom: 10px;">{{ $product->title }}</h1>
                    <p style="font-size: 1.8em; color: #00674F; font-weight: bold; margin-bottom: 20px;">
                        ₱{{ number_format($product->price, 2) }}</p>
                    <p style="font-size: 1.1em; color: #555; line-height: 1.6; margin-bottom: 25px;">{{ $product->desc }}</p>

                    <div class="stock-info" style="font-size: 1.1em; margin-bottom: 25px;">
                        @if ($product->stock > 0)
                            <span style="color: green; font-weight: bold;">In Stock ({{ $product->stock }} available)</span>
                        @else
                            <span style="color: red; font-weight: bold;">Out of Stock</span>
                        @endif
                    </div>

                    {{-- Size Dropdown --}}
                        @if ($sizes->count())
                            {{-- Size Dropdown --}}
                            <div class="size-section" style="margin-bottom: 20px;">
                                <label for="size" style="font-weight: bold; font-size: 1.1em;">Select Size:</label>
                                <select id="size" name="size"
                                    style="width: 100px; padding: 10px; border: 1px solid #ccc; border-radius: 5px; font-size: 1em;">
                                    <option value="" selected disabled>Choose size</option>
                                    @foreach ($sizes as $size)
                                        <option value="{{ $size }}">{{ $size }}</option>
                                    @endforeach
                                </select>
                            </div>
                        @else
                            <div class="size-section" style="margin-bottom: 20px;">
                                <label style="font-weight: bold; font-size: 1.1em;">Size:</label>
                                <p>No sizes available</p>
                            </div>
                        @endif

                    {{-- Add to Cart --}}
                    <div class="add-to-cart-section" style="display: flex; align-items: center; gap: 20px;">
                        <label for="quantity" style="font-weight: bold; font-size: 1.1em;">Quantity:</label>
                        <input type="number" id="quantity" value="1" min="1" max="{{ $product->stock }}"
                               style="width: 80px; padding: 10px; border: 1px solid #ccc; border-radius: 5px; font-size: 1em;">
                        <button id="add-to-cart-btn-detail" class="add-to-cart-btn" data-product-id="{{ $product->id }}"
                                data-product-stock="{{ $product->stock }}" data-product-title="{{ $product->title }}"
                                style="padding: 12px 25px; background-color: #00674F; color: white; border: none; border-radius: 30px; cursor: pointer; font-size: 1.1em; font-weight: bold; transition: background-color 0.3s;"
                                {{ $product->stock <= 0 ? 'disabled' : '' }}>
                            Add to Cart
                        </button>
                    </div>
                </div>
            </div>
        @else
            <p style="text-align: center; font-size: 1.2em; color: #888;">Product not found.</p>
        @endif
    </section>

    @push('scripts')
        <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
        <script>
            $(document).ready(function () {
                $('#add-to-cart-btn-detail').on('click', function () {
                    let productId = $(this).data('product-id');
                    let productStock = $(this).data('product-stock');
                    let productTitle = $(this).data('product-title');
                    let quantity = parseInt($('#quantity').val());
                    let size = $('#size').val();

                    if (!size) {
                        Swal.fire('Missing Size', 'Please select a size.', 'warning');
                        return;
                    }

                    if (isNaN(quantity) || quantity < 1) {
                        Swal.fire('Invalid Quantity', 'Please enter a valid quantity.', 'warning');
                        return;
                    }

                    if (quantity > productStock) {
                        Swal.fire('Stock Limit Reached', 'You cannot add more than ' + productStock + ' of ' + productTitle + ' to your cart.', 'error');
                        return;
                    }

                    $.ajax({
                        url: "{{ route('clientAddToCart') }}",
                        method: "POST",
                        data: {
                            _token: "{{ csrf_token() }}",
                            product_id: productId,
                            quantity: quantity,
                            size: size
                        },
                        success: function (response) {
                            if (response.status === 'success') {
                                Swal.fire({
                                    icon: 'success',
                                    title: 'Added to Cart!',
                                    text: productTitle + ' (' + size + ', x' + quantity + ') has been added to your cart.'
                                });
                                updateCartCount(response.cartCount);
                            } else if (response.status === 'failed' && response.code === 202) {
                                Swal.fire('Error', 'Requested quantity exceeds stock.', 'error');
                            } else {
                                Swal.fire('Error', 'Something went wrong. Please try again.', 'error');
                            }
                        },
                        error: function (xhr) {
                            console.error("AJAX Error:", xhr.responseText);
                            Swal.fire('Error', 'An error occurred while adding to cart.', 'error');
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
