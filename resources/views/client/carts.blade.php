@extends('client.layouts.app')

@section('content')
    <section class="container" style="padding: 40px 0;">
        <h2 class="section-heading">Your Shopping Cart</h2>

        @php
            $cart = session()->get('cart');
            $total = 0;
        @endphp

        @if ($cart && count($cart) > 0)
            <div class="cart-table-container" style="overflow-x: auto;">
                <table class="cart-table" style="width: 100%; border-collapse: collapse; margin-bottom: 30px;">
                    <thead>
                        <tr style="background-color: #f2f2f2;">
                            <th style="padding: 12px 15px; text-align: left; border-bottom: 1px solid #ddd;">Product</th>
                            <th style="padding: 12px 15px; text-align: left; border-bottom: 1px solid #ddd;">Price</th>
                            <th style="padding: 12px 15px; text-align: center; border-bottom: 1px solid #ddd;">Quantity</th>
                            <th style="padding: 12px 15px; text-align: left; border-bottom: 1px solid #ddd;">Subtotal</th>
                            <th style="padding: 12px 15px; text-align: center; border-bottom: 1px solid #ddd;">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($cart as $id => $details)
                            @php
                                $subtotal = $details['price'] * $details['quantity'];
                                $total += $subtotal;
                            @endphp
                            <tr data-id="{{ $id }}">
                                <td style="padding: 12px 15px; border-bottom: 1px solid #ddd;">{{ $details['title'] }}</td>
                                <td style="padding: 12px 15px; border-bottom: 1px solid #ddd;">
                                    ₱{{ number_format($details['price'], 2) }}</td>
                                <td style="padding: 12px 15px; border-bottom: 1px solid #ddd; text-align: center;">
                                    <input type="number" value="{{ $details['quantity'] }}" min="1"
                                        max="{{ $details['product_stock'] ?? 9999 }}" class="quantity-input"
                                        style="width: 60px; padding: 5px; border: 1px solid #ccc; border-radius: 4px; text-align: center;">
                                </td>
                                <td class="item-subtotal" style="padding: 12px 15px; border-bottom: 1px solid #ddd;">
                                    ₱{{ number_format($subtotal, 2) }}</td>
                                <td style="padding: 12px 15px; border-bottom: 1px solid #ddd; text-align: center;">
                                    <button class="remove-from-cart-btn btn-danger" data-id="{{ $id }}"
                                        data-product-title="{{ $details['title'] }}">Remove</button>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="cart-actions-bottom"
                style="display: flex; justify-content: space-between; align-items: center; margin-top: 30px; flex-wrap: wrap; gap: 20px;">
                <a href="{{ route('clientProducts') }}" class="btn-more" style="text-decoration: none;">← Continue
                    Shopping</a>
                <div class="cart-summary-checkout"
                    style="display: flex; flex-direction: column; align-items: flex-end; gap: 15px;">
                    <h3 style="font-size: 1.5em; color: #333; margin: 0;">Total: <span id="cart-total"
                            style="color: #5b21b6;">₱{{ number_format($total, 2) }}</span></h3>
                    @guest
                        <a href="{{ route('login') }}" class="btn-primary-custom" style="text-decoration: none;">Login to
                            Checkout / Register</a>
                        <p class="mt-2 text-muted" style="font-size: 0.9em; margin-top: 5px;">Please login or register to
                            proceed with your order.</p>
                    @else
                        {{-- Assuming you have a checkout route for logged in users --}}
                        <a href="{{ route('clientCheckout') }}" class="btn-primary-custom"
                            style="text-decoration: none;">Proceed to Checkout</a>
                    @endguest
                </div>
            </div>
        @else
            <p style="text-align: center; font-size: 1.2em; color: #888;">Your cart is empty.</p>
            <div style="text-align: center; margin-top: 30px;">
                <a href="{{ route('clientProducts') }}" class="btn-more">Start Shopping Now!</a>
            </div>
        @endif
    </section>

    @push('scripts')
        <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script> {{-- Added SweetAlert2 --}}

        <script>
            $(document).ready(function() {
                // Function to update cart total on the page and global cart count
                function refreshCartTotal(newTotal, newCartCount) {
                    $('#cart-total').text('₱' + newTotal.toLocaleString('en-PH', {
                        minimumFractionDigits: 2,
                        maximumFractionDigits: 2
                    }));
                    if (typeof updateCartCount === 'function') { // Check if global function exists
                        updateCartCount(newCartCount);
                    }
                }

                // Update Cart Quantity
                $('.quantity-input').on('change', function() {
                    let $input = $(this);
                    let productId = $input.closest('tr').data('id');
                    let newQuantity = parseInt($input.val());
                    let maxStock = parseInt($input.attr('max'));

                    if (isNaN(newQuantity) || newQuantity < 1) {
                        newQuantity = 1;
                        $input.val(1);
                    }

                    if (newQuantity > maxStock) {
                        Swal.fire({ // Use SweetAlert2 for stock warning
                            icon: 'warning',
                            title: 'Stock Limit',
                            text: 'Maximum stock for this product is ' + maxStock + '.',
                        });
                        newQuantity = maxStock;
                        $input.val(maxStock);
                    }

                    if (newQuantity !== parseInt($input.data('old-quantity'))) {
                        $.ajax({
                            url: "{{ route('clientUpdateCart') }}",
                            method: "POST",
                            data: {
                                _token: "{{ csrf_token() }}",
                                product_id: productId,
                                quantity: newQuantity
                            },
                            success: function(response) {
                                let price = parseFloat($input.closest('tr').find('td:nth-child(2)')
                                    .text().replace('₱', '').replace(/,/g, '')
                                );
                                let newSubtotal = price * newQuantity;
                                $input.closest('tr').find('.item-subtotal').text('₱' + newSubtotal
                                    .toLocaleString('en-PH', {
                                        minimumFractionDigits: 2,
                                        maximumFractionDigits: 2
                                    }));

                                refreshCartTotal(response.total, response.cartCount);
                            },
                            error: function(xhr) {
                                console.error("AJAX Error:", xhr.responseText);
                                Swal.fire({ // Use SweetAlert2 for error
                                    icon: 'error',
                                    title: 'Error',
                                    text: 'Error updating cart. Please try again.',
                                });
                                $input.val($input.data('old-quantity'));
                            }
                        });
                    }
                }).on('focus', function() {
                    $(this).data('old-quantity', $(this).val());
                });

                // Remove from Cart with SweetAlert confirmation and success message
                $('.remove-from-cart-btn').on('click', function() {
                    let productId = $(this).data('id');
                    let productTitle = $(this).data('product-title'); // Get product title for the message

                    Swal.fire({
                        title: 'Are you sure?',
                        text: "Do you want to remove " + productTitle + " from your cart?",
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#d33',
                        cancelButtonColor: '#6c757d',
                        confirmButtonText: 'Yes, remove it!'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            $.ajax({
                                url: "{{ route('clientDeleteCart') }}",
                                method: "POST",
                                data: {
                                    _token: "{{ csrf_token() }}",
                                    id: productId
                                },
                                success: function(response) {
                                    $('[data-id="' + productId + '"]')
                                        .remove(); // Remove the row
                                    refreshCartTotal(response.total, response.cartCount);

                                    Swal.fire( // Success message after removal
                                        'Removed!',
                                        productTitle +
                                        ' has been removed from your cart.',
                                        'success'
                                    );

                                    if (response.cartCount === 0) {
                                        // Show empty cart message and remove other elements
                                        $('.cart-table-container').html(
                                            '<p style="text-align: center; font-size: 1.2em; color: #888;">Your cart is empty.</p>'
                                        );
                                        $('.cart-actions-bottom').html(
                                            '<div style="text-align: center; margin-top: 30px; width: 100%;"><a href="{{ route('clientProducts') }}" class="btn-more">Start Shopping Now!</a></div>'
                                        );
                                    }
                                },
                                error: function(xhr) {
                                    console.error("AJAX Error:", xhr.responseText);
                                    Swal.fire({ // Error message
                                        icon: 'error',
                                        title: 'Error',
                                        text: 'Error removing from cart. Please try again.',
                                    });
                                }
                            });
                        }
                    });
                });
            });
        </script>
        {{-- Add custom styles for buttons consistent with your home page's .btn-more and .btn-primary --}}
        <style>
            /* Base button styles from your home page */
            .btn-more {
                display: inline-block;
                padding: 10px 20px;
                background-color: #5b21b6;
                /* Your primary purple */
                color: white;
                text-align: center;
                border-radius: 5px;
                text-decoration: none;
                font-weight: bold;
                transition: background-color 0.3s ease;
            }

            .btn-more:hover {
                background-color: #4a1c99;
                /* Darker purple on hover */
            }

            .btn-more .arrow {
                margin-left: 5px;
                font-size: 1.2em;
            }

            /* Custom primary button for checkout/login, similar to btn-more */
            .btn-primary-custom {
                display: inline-block;
                padding: 12px 25px;
                /* Slightly larger padding */
                background-color: #5b21b6;
                color: white;
                text-align: center;
                border-radius: 5px;
                text-decoration: none;
                font-weight: bold;
                transition: background-color 0.3s ease;
                white-space: nowrap;
                /* Prevent text wrap */
            }

            .btn-primary-custom:hover {
                background-color: #4a1c99;
            }

            /* Red button for remove action */
            .btn-danger {
                background-color: #dc3545;
                /* Bootstrap red */
                color: white;
                border: none;
                padding: 8px 12px;
                border-radius: 4px;
                cursor: pointer;
                transition: background-color 0.3s ease;
            }

            .btn-danger:hover {
                background-color: #c82333;
                /* Darker red on hover */
            }

            /* Flex adjustments for smaller screens */
            @media (max-width: 768px) {
                .cart-actions-bottom {
                    flex-direction: column;
                    align-items: center;
                }

                .cart-summary-checkout {
                    align-items: center;
                    /* Center align items in summary for small screens */
                }
            }
        </style>
    @endpush
@endsection
