@extends('layouts.template')

@section('title', 'Edit Category')

@section('content')
<link rel="stylesheet" href="{{ asset('css/add_product.css') }}">

<div class="category-container">
    <form action="{{ route('admin.category_update', $category->id) }}" method="POST" class="category-form" enctype="multipart/form-data">
        <h2 class="form-title">Edit Category</h2>
        @csrf
        @method('PUT')
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
            <label for="name">Name</label>
            <input type="text" name="name" id="name" value="{{ old('name', $category->name) }}" >
        </div>
        <div class="form-group">
            <label for="description">Description</label>
            <textarea name="description" id="descriprion" rows="3">{{ old('description', $category->description) }}</textarea>
        </div>
        <div class="form-group">
            <label for="status">Status</label>
            <select name="status" id="status">
                <option value="">-- Select Status --</option>
                @foreach(['Active', 'Inctive'] as $status)
                    <option value="{{ $status }}" {{ old('status') == $status ? 'selected' : '' }}>{{ $status }}</option>
                @endforeach
            </select>
        </div>
        <div class="form-group">
            <label for="image">Category Image</label>
            <input type="file" name="image" id="image" accept="image/*">
            @if ($category->image)
                <div style="margin-top:10px;">
                    <img src="{{ asset('category/' . basename($category->image)) }}" alt="Current Image" style="width:70px; height:70px; object-fit:cover;">
                </div>
            @endif
            @if ($errors->has('image'))
                <span class="text-danger">{{ $errors->first('image') }}</span>
            @endif
        </div>
        <button type="submit" class="btn-add-category"><i class="fa fa-save"></i> Save Category</button>
    </form>
</div>
@endsection