@extends('layouts.template')

@section('title', 'Add Product')

@section('content')
<link rel="stylesheet" href="{{ asset('css/add_product.css') }}">

<div class="product-container">
    <form action="{{ route('admin.product_store') }}" method="POST" class="product-form" enctype="multipart/form-data">
        <h2 class="form-title">Add Product</h2>
        @csrf

        @if ($errors->any())
            <div class="alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="form-group">
            <label for="title">Name</label>
            <input type="text" name="title" id="title" value="{{ old('title') }}" >
        </div>
        <div class="form-group">
            <label for="desc">Description</label>
            <textarea name="desc" id="desc" rows="3">{{ old('desc') }}</textarea>
        </div>
        <div class="form-group">
            <label for="size">Size</label>
            <select name="size" id="size" >
                <option value="">-- Select Size --</option>
                @foreach(['XS', 'S', 'M', 'L', 'XL', 'XXL'] as $size)
                    <option value="{{ $size }}" {{ old('size') == $size ? 'selected' : '' }}>{{ $size }}</option>
                @endforeach
            </select>
        </div>
        <div class="form-group">
            <label for="price">Price (₱)</label>
            <input type="number" name="price" id="price" value="{{ old('price') }}" step="0.01" min="0">
        </div>
        <div class="form-group">
            <label for="stock">Stock</label>
            <input type="number" name="stock" id="stock" value="{{ old('stock') }}" min="0">
        </div>
        <div class="form-group">
            <label for="category_id">Category</label>
            <select name="category_id" id="category_id">
                <option value="">-- Select Category --</option>
                @foreach($categories as $category)
                    <option value="{{ $category->id }}" {{ old('category_id')==$category->id ? 'selected' : '' }}>
                        {{ $category->name }}
                    </option>
                @endforeach
            </select>
        </div>
        <div class="form-group">
            <label for="date_made">Date Made</label>
            <input type="date" name="date_made" id="date_made" value="{{ old('date_made') }}" required>
        </div>
        <div class="form-group">
            <label for="image">Product Image</label>
            <input type="file" name="image" id="image" accept="image/*">
            @if ($errors->has('image'))
                <span class="text-danger">{{ $errors->first('image') }}</span>
            @endif
        </div>
        <button type="submit" class="btn-add-product"><i class="fa fa-save"></i> Save Product</button>
    </form>
</div>
@endsection