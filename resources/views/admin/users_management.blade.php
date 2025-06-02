@extends('layouts.template')

@section('title', 'Users Management')

@section('content')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<link rel="stylesheet" href="{{ asset('css/admin_users.css') }}">

<div class="users-container">
    <div class="users-header">
        <h2>Users Management</h2>
        <a href="{{ route('admin.user_create') }}" class="btn-add-users">
            <i class="fa fa-plus"></i> Add Users
        </a>
    </div>

    <!-- Search Form -->
    <div class="alert-error" style="display: none; color: red;" id="error-search"></div>
    <form method="GET" action="{{ url()->current() }}" class="search-form">
        <input type="text" name="search" placeholder="Search users..." value="{{ request('search') }}" id="search-input">
        <button type="submit">
            <i class="fa fa-search"></i> Search
        </button>
    </form>

    @if(session('success'))
        <div class="alert-success" id="success">{{ session('success') }}</div>
    @else  
        <div class="alert-success" style="display: none;" id="success"></div>
    @endif

    <table class="users-table">
        <thead>
            <tr>
                <th>ID</th>
                <th>Name</th>
                <th>Email</th>
                <th>Image</th>
                <th>Phone Number</th>
                <th>Status</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
        @forelse($users as $user)
            <tr>
                <td>{{ $loop->iteration }}</td>
                <td>{{ $user->name }}</td>
                <td>{{ $user->email }}</td>
                <td>
                    @if($user->image_users)
                        <img src="{{ asset('images/' . basename($user->image_users)) }}" alt="Image" class="image" style="width: 70px; height: 70px; object-fit: cover;">
                    @else
                        <span class="no-image">No Image</span>
                    @endif
                </td>
                <td>{{ $user->phone }}</td>
                <td>{{ $user->status }}</td>
                <td>
                    <a href="" class="btn-action btn-edit">
                        <i class="fa fa-edit"></i> Edit
                    </a>
                    <form action="{{ route('admin.users_destroy', $user->id) }}" method="POST" style="display:inline;" class="delete-form">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn-action btn-delete">
                            <i class="fa fa-trash"></i> Delete
                        </button>
                    </form>
                </td>
            </tr>
        @empty
            <tr><td colspan="7" style="text-align:center;">No users found.</td></tr>
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
                title: 'Delete this user?',
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
