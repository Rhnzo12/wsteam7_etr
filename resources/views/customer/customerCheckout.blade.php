@extends('customer.layouts.apps')

@section('content')
<section class="checkout-form-container">

<div class="form-wrapper">
    <h2>Checkout</h2>
    <form action="{{ route('orders.store') }}" method="POST" class="checkout-form">
        @csrf

        <!-- Form Fields -->
        <div class="form-group">
            <label for="name">Name:</label>
            <input type="text" id="name" name="name" required value="{{ old('name') }}" class="@error('name') input-error @enderror">
            @error('name') <div class="error-message">{{ $message }}</div> @enderror
        </div>

        <div class="form-group">
            <label for="address">Address:</label>
            <textarea id="address" name="address" required class="@error('address') input-error @enderror">{{ old('address') }}</textarea>
            @error('address') <div class="error-message">{{ $message }}</div> @enderror
        </div>

        <div class="form-group">
            <label for="number">Phone Number:</label>
            <input type="text" id="number" name="number" required value="{{ old('number') }}" class="@error('number') input-error @enderror">
            @error('number') <div class="error-message">{{ $message }}</div> @enderror
        </div>

        <div class="form-group">
            <label for="pay_mode">Payment Mode:</label>
            <select name="pay_mode" id="pay_mode" required>
                <option value="cash on delivery">Cash on Delivery</option>
                <option value="online payment">Online Payment</option>
            </select>
            @error('pay_mode') <div class="error-message">{{ $message }}</div> @enderror
        </div>

        <!-- Summary Section -->
        @if(count($cart) > 0)
            <div class="checkout-summary">
                <h3>Your Order Summary</h3>

                @php $totalAmount = 0; @endphp

                @foreach($cart as $id => $item)
                    @php
                        $qty = $item['quantity'];
                        $price = $item['price'];
                        $title = $item['title'];
                        $subtotal = $qty * $price;
                    @endphp

                    <div class="summary-item" 
                         data-id="{{ $id }}" 
                         data-title="{{ $title }}"
                         data-qty="{{ $qty }}"
                         data-price="{{ $price }}"
                         data-subtotal="{{ $subtotal }}">
                        <strong>Product:</strong> {{ $title }}<br>
                        <strong>Quantity:</strong> {{ $qty }}<br>
                        <strong>Price:</strong> ₱{{ number_format($price, 2) }}<br>
                        <strong>Discount:</strong> <span class="discount">None</span><br>
                        <strong>Subtotal:</strong> ₱<span class="subtotal">{{ number_format($subtotal, 2) }}</span>
                    </div>
                    <hr>
                @endforeach

                <div class="summary-total">
                    <strong>Total Amount:</strong> ₱<span id="grandTotal">0.00</span>
                </div>
            </div>
        @else
            <p>Your cart is empty.</p>
        @endif

        <button type="submit" class="btn-submit">Checkout</button>
    </form>
</div>
</section>

<!-- JS to update summary dynamically -->
<script>
    function updateSummary() {
        const payMode = document.getElementById('pay_mode').value;
        let grandTotal = 0;

        document.querySelectorAll('.summary-item').forEach(item => {
            const qty = parseInt(item.dataset.qty);
            const price = parseFloat(item.dataset.price);
            const subtotal = qty * price;

            let discountRate = 0;
            if (payMode === 'online payment') {
                discountRate = qty >= 3 ? 0.12 : 0.10;
            }

            const discountAmount = subtotal * discountRate;
            const discountedSubtotal = subtotal - discountAmount;

            item.querySelector('.discount').innerText = discountRate > 0 ? `${(discountRate * 100).toFixed(0)}%` : 'None';
            item.querySelector('.subtotal').innerText = discountedSubtotal.toFixed(2);

            grandTotal += discountedSubtotal;
        });

        document.getElementById('grandTotal').innerText = grandTotal.toFixed(2);
    }

    document.getElementById('pay_mode').addEventListener('change', updateSummary);
    window.addEventListener('load', updateSummary);
</script>

<!-- Styles -->
<style>
    .form-wrapper {
        max-width: 500px;
        margin: 0 auto;
        padding: 20px;
    }
    .form-group {
        margin-bottom: 15px;
    }

    label {
        display: block;
        font-weight: 600;
        margin-bottom: 5px;
        color: #00674F;
    }

    input[type="text"], textarea, select {
        width: 100%;
        padding: 8px 10px;
        border: 1px solid #ccc;
        border-radius: 4px;
        font-size: 1rem;
    }

    .input-error {
        border-color: #d9534f;
    }

    .error-message {
        color: #d9534f;
        font-size: 0.875rem;
        margin-top: 4px;
    }

    .checkout-summary {
        background-color: #f9f9f9;
        padding: 20px;
        margin: 25px 0;
        border-radius: 6px;
        border: 1px solid #ddd;
    }

    .checkout-summary h3 {
        margin-bottom: 15px;
        color: #00674F;
        font-weight: 700;
        font-size: 1.3rem;
    }

    .summary-item {
        margin-bottom: 15px;
        font-size: 1rem;
        color: #333;
    }

    .summary-item strong {
        color: #00674F;
    }

    hr {
        border: none;
        border-top: 1px solid #ddd;
        margin: 10px 0;
    }

    .summary-total {
        font-size: 1.25rem;
        font-weight: 700;
        text-align: right;
        color: #00674F;
        margin-top: 10px;
    }

    button.btn-submit {
        background-color: #00674F;
        color: white;
        padding: 12px 20px;
        border: none;
        border-radius: 6px;
        font-size: 1.1rem;
        cursor: pointer;
        transition: background-color 0.3s ease;
        margin-top: 15px;
        width: 100%;
    }

    button.btn-submit:hover {
        background-color: #004d33;
    }
</style>
@endsection
