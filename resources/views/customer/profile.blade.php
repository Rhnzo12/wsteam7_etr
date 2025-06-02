@extends('layouts.app')

@section('content')
<div class="container">
    <h2>Customer Profile</h2>
    <div class="row">
        <!-- Profile Details -->
        <div class="col-md-6">
            <div class="card mb-4">
                <div class="card-body text-center">
                    @if($customer->profile_picture)
                        <img src="{{ asset('storage/' . $customer->profile_picture) }}" alt="Profile Picture" class="rounded-circle mb-3" width="120" height="120">
                    @else
                        <img src="{{ asset('images/default-profile.png') }}" alt="Default Profile Picture" class="rounded-circle mb-3" width="120" height="120">
                    @endif
                    <h5 class="card-title">{{ $customer->name }}</h5>
                    <p class="card-text"><strong>Email:</strong> {{ $customer->email }}</p>
                    <p class="card-text"><strong>Phone:</strong> {{ $customer->phone ?? 'N/A' }}</p>
                    <p class="card-text"><strong>Address:</strong> {{ $customer->address ?? 'N/A' }}</p>
                </div>
            </div>
        </div>
        <!-- Edit Profile Form -->
        <div class="col-md-6">
            <div class="card mb-4">
                <div class="card-body">
                    <h5>Edit Profile</h5>
                    <form action="{{ route('customer.update', $customer->id) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
                        <div class="mb-3">
                            <label for="name" class="form-label">Name</label>
                            <input type="text" name="name" class="form-control" value="{{ old('name', $customer->name) }}" required>
                        </div>
                        <div class="mb-3">
                            <label for="email" class="form-label">Email</label>
                            <input type="email" name="email" class="form-control" value="{{ old('email', $customer->email) }}" required>
                        </div>
                        <div class="mb-3">
                            <label for="phone" class="form-label">Phone</label>
                            <input type="text" name="phone" class="form-control" value="{{ old('phone', $customer->phone) }}">
                        </div>
                        <div class="mb-3">
                            <label for="address" class="form-label">Address</label>
                            <input type="text" name="address" class="form-control" value="{{ old('address', $customer->address) }}">
                        </div>
                        <div class="mb-3">
                            <label for="profile_picture" class="form-label">Profile Picture</label>
                            <input type="file" name="profile_picture" class="form-control">
                        </div>
                        <button type="submit" class="btn btn-success">Save Changes</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection