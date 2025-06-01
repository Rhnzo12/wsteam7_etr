@extends('layouts.template')

@section('title', 'Product Management')

@section('content')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<link rel="stylesheet" href="{{ asset('css/admin_product.css') }}">
<div class="product-container">
    <div class="product-header">
    <h2>Product Management</h2>
        <a href="{{ route('admin.product_create') }}" class="btn-add-product">
            <i class="fa fa-plus"></i> Add Product
        </a>
    </div>

    <!-- Search Form -->
     <div class="alert-error" style="display: none; color: red;" id="error-search"></div>
    <form method="GET" action="{{ url()->current() }}" class="search-form">
        <input type="text" name="search" placeholder="Search products..." value="{{ request('search') }}" id="search-input">
        <button type="submit">
            <i class="fa fa-search"></i> Search
        </button>
    </form>

    @if(session('success'))
        <div class="alert-success" id="success">{{ session('success') }}</div>
    @else  
        <div class="alert-success" style="display: none;" id="success"></div>
    @endif

    <table class="product-table">
        <thead>
            <tr>
                <th>ID</th>
                <th>Name</th>
                <th>Description</th>
                <th>Size</th>
                <th>Price</th>
                <th>Stock</th>
                <th>Category</th>
                <th>Date Made</th>
                <th>Product Image</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
        @forelse($products as $product)
            
        
            <tr>
                <td>{{ $loop->iteration }}</td>
                <td>{{ $product->title }}</td>
                <td>{{ $product->desc }}</td>
                <td>{{ $product->size }}</td>
                <td>{{ $product->price }}</td>
                <td>{{ $product->stock }}</td>
                <td>{{ $product->category_name }}</td>
                <td>{{ $product->date_made }}</td>
                <td>
                    @if($product->image_path)
                        <img src="{{ asset('product/' . basename($product->image_path)) }}" alt="Product Image" class="product-image" style="width: 70px; height: 70px; object-fit: cover;">
                    @else
                        <span class="no-image">No Image</span>
                    @endif
                <td>
                    <a href="{{ route('admin.product_edit', $product->id) }}" class="btn-action btn-edit">
                        <i class="fa fa-edit"></i> Edit
                    </a>
                    <form action="{{ route('admin.product_destroy', $product->id) }}" method="POST" style="display:inline;" class="delete-form">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn-action btn-delete">
                            <i class="fa fa-trash"></i> Delete
                        </button>
                    </form>
                </td>
            </tr>
        @empty
            <tr><td colspan="9" style="text-align:center;">No products found.</td></tr>
        @endforelse
        </tbody>
    </table>
</div>
<script>
    $(document).ready(function() {
        $('.search-form').submit(function(e) {
            if(!$('#search-input').val()){
                $('#error-search').show().text('Search input cannot be empty').fadeOut(3000);
                e.preventDefault();
            }
        });
        $('.delete-form').submit(function(e) {
            e.preventDefault();
            const form = this;
            Swal.fire({
                title: 'Delete this product?',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Yes, delete'
            }).then(result => {
                if (result.isConfirmed) form.submit();
            });
        });
        $('#success').fadeOut(3000);

    });
</script>
@endsection