@extends('layouts.template')

@section('title', 'Add User')

@section('content')
<link rel="stylesheet" href="{{ asset('css/add_user.css') }}">

<div class="user-container">
    <form action="{{ route('admin.user_store') }}" method="POST" class="user-form" enctype="multipart/form-data">
        @csrf
        <h2 class="form-title">Add User</h2>

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
            <input type="text" name="name" id="name" value="{{ old('name') }}" >
        </div>

        <div class="form-group">
            <label for="email">Email</label>
            <input type="email" name="email" id="email" value="{{ old('email') }}">
        </div>

        <div class="form-group">
            <label for="image_users">Image</label>
            <input type="file" name="image_users" id="image_users" accept="image/*">
            @if ($errors->has('image_users'))
                <span class="text-danger">{{ $errors->first('image_users') }}</span>
            @endif
        </div>

        <div class="form-group">
            <label for="phone">Phone Number</label>
            <input type="text" name="phone" id="phone" value="{{ old('phone') }}">
        </div>

        <button type="submit" class="btn-add-user"><i class="fa fa-save"></i> Save User</button>
    </form>
</div>
@endsection
