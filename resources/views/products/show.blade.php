@extends('layouts.app')

@section('content')
<h2>{{ $product->name }}</h2>
<p>{{ $product->description }}</p>
<p><strong>Price:</strong> ${{ $product->price }}</p>
<p><strong>Stock:</strong> {{ $product->stock }}</p>

<form method="POST" action="/cart/add">
    @csrf
    <input type="hidden" name="product_id" value="{{ $product->id }}">
    <input type="number" name="quantity" value="1" min="1">
    <button type="submit">Add to Cart</button>
</form>

<h4>Reviews:</h4>
@foreach($product->reviews as $review)
    <p><strong>{{ $review->user->name }}</strong>: {{ $review->rating }} stars</p>
    <p>{{ $review->comment }}</p>
@endforeach

<h4>Leave a Review:</h4>
<form method="POST" action="/products/{{ $product->id }}/review">
    @csrf
    <label>Rating (1–5):</label>
    <input type="number" name="rating" min="1" max="5" required>
    <textarea name="comment" placeholder="Comment (optional)"></textarea>
    <button type="submit">Submit Review</button>
</form>
@endsection
