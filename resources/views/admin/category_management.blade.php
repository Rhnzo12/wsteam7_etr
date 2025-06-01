@extends('layouts.template')

@section('title', 'Category Management')

@section('content')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<link rel="stylesheet" href="{{ asset('css/admin_category.css') }}">
<div class="category-container">
    <div class="category-header">
    <h2>Category Management</h2>
        <a href="{{ route('admin.category_create') }}" class="btn-add-category">
            <i class="fa fa-plus"></i> Add Category
        </a>
    </div>

    <!-- Search Form -->
     <div class="alert-error" style="display: none; color: red;" id="error-search"></div>
    <form method="GET" action="{{ url()->current() }}" class="search-form">
        <input type="text" name="search" placeholder="Search category..." value="{{ request('search') }}" id="search-input">
        <button type="submit">
            <i class="fa fa-search"></i> Search
        </button>
    </form>

    @if(session('success'))
        <div class="alert-success" id="success">{{ session('success') }}</div>
    @else  
        <div class="alert-success" style="display: none;" id="success"></div>
    @endif
    @if(session('error'))
        <div class="alert-error" id="error">{{ session('error') }}</div>
    @else  
        <div class="alert-error" style="display: none;" id="error"></div>
    @endif

    <table class="category-table">
        <thead>
            <tr>
                <th>ID</th>
                <th>Name</th>
                <th>Description</th>
                <th>Status</th>
                <th>Image</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
        @forelse($categories as $category)
            
        
            <tr>
                <td>{{ $loop->iteration }}</td>
                <td>{{ $category->name }}</td>
                <td>{{ $category->description }}</td>
                <td>{{ $category->status }}</td>
                <td>
                    @if($category->image)
                        <img src="{{ asset('category/' . basename($category->image)) }}" alt="Category Image" class="category-image" style="width: 70px; height: 70px; object-fit: cover;">
                    @else
                        <span class="no-image">No Image</span>
                    @endif
                <td>
                    <a href="{{ route('admin.category_edit', $category->id) }}" class="btn-action btn-edit">
                        <i class="fa fa-edit"></i> Edit
                    </a>
                    <form action="{{ route('admin.category_destroy', $category->id) }}" method="POST" style="display:inline;" class="delete-form">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn-action btn-delete">
                            <i class="fa fa-trash"></i> Delete
                        </button>
                    </form>
                </td>
            </tr>
        @empty
            <tr><td colspan="9" style="text-align:center;">No Category found.</td></tr>
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
                title: 'Delete this category?',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Yes, delete'
            }).then(result => {
                if (result.isConfirmed) form.submit();
            });
        });
        $('#success').fadeOut(5000);
        $('#error').fadeOut(5000);

    });
</script>
@endsection