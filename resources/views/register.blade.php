<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <title>Register</title>
</head>
<body style="background-color: #00674F;">
    <div class="container-fluid p-0">
            <a href="/" class="d-flex align-items-center text-decoration-none">
                <img src="{{ asset('images/logo.png') }}" alt="Logo" style="height:80px; width:120px; object-fit:contain;">
            </a>
        <div style="display: flex; justify-content: center; align-items: center; height: 90vh;">
            <div style="background:#F8EDD5; padding: 2rem; border-radius: 8px; box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1); width: 100%; max-width: 600px;">
                @if(session('success'))
                    <p style="color: green; text-align: center; margin-bottom: 1rem;">{{ session('success') }}</p>
                @endif
                @if(session('error'))
                    <p style="color: red; text-align: center; margin-bottom: 1rem;">{{ session('error') }}</p>
                @endif

                <h2 style="text-align: center; margin-bottom: 1.5rem; color: #333;">Register</h2>
                <form action="{{ route('register') }}" method="POST" style="display: flex; flex-direction: column; gap: 1rem;">
                    @csrf
                    <div>
                        <label for="name" style="display: block; margin-bottom: 0.5rem; color: #555; font-weight:bold;">Name</label>
                        <input type="text" id="name" name="name" value="{{ old('name') }}" placeholder="Enter your name" 
                            style="width: 100%; padding: 0.75rem; border: none; border-bottom: 1px solid #00674F; border-radius: 0; font-size: 1rem; background-color: #F8EDD5; box-shadow: 0 2px 4px #0000000A; outline: none;">
                        @error('name') 
                            <span style="color: red; font-size: 0.875rem;">{{ $message }}</span>
                        @enderror
                    </div>
                    <div>
                        <label for="email" style="display: block; margin-bottom: 0.5rem; color: #555; font-weight:bold;">Email</label>
                        <input type="text" id="email" name="email" value="{{ old('email') }}" placeholder="Enter your email" 
                            style="width: 100%; padding: 0.75rem; border: none; border-bottom: 1px solid #00674F; border-radius: 0; font-size: 1rem; background-color: #F8EDD5; box-shadow: 0 2px 4px #0000000A; outline: none;">
                        @error('email') 
                            <span style="color: red; font-size: 0.875rem;">{{ $message }}</span>
                        @enderror
                    </div>
                    <div>
                        <label for="phone" style="display: block; margin-bottom: 0.5rem; color: #555; font-weight:bold;">Phone Number</label>
                        <input type="phone" id="phone" name="phone" value="{{ old('phone') }}" placeholder="Enter your phone number" 
                            style="width: 100%; padding: 0.75rem; border: none; border-bottom: 1px solid #00674F; border-radius: 0; font-size: 1rem; background-color: #F8EDD5; box-shadow: 0 2px 4px #0000000A; outline: none;">
                        @error('phone') 
                            <span style="color: red; font-size: 0.875rem;">{{ $message }}</span>
                        @enderror
                    </div>
                    <div>
                        <label for="password" style="display: block; margin-bottom: 0.5rem; color: #555; font-weight:bold;">Password</label>
                        <input type="password" id="password" name="password" placeholder="Enter your password" 
                            style="width: 100%; padding: 0.75rem; border: none; border-bottom: 1px solid #00674F; border-radius: 0; font-size: 1rem; background-color: #F8EDD5; box-shadow: 0 2px 4px #0000000A; outline: none;">
                        @error('password') 
                            <span style="color: red; font-size: 0.875rem;">{{ $message }}</span>
                        @enderror
                    </div>
                    <div>
                        <label for="password_confirmation" style="display: block; margin-bottom: 0.5rem; color: #555; font-weight:bold;">Confirm Password</label>
                        <input type="password" id="password_confirmation" name="password_confirmation" placeholder="Confirm your password" 
                           style="width: 100%; padding: 0.75rem; border: none; border-bottom: 1px solid #00674F; border-radius: 0; font-size: 1rem; background-color: #F8EDD5; box-shadow: 0 2px 4px #0000000A; outline: none;">
                    </div>
                    <div class="d-flex justify-content-between align-items-center mt-3">
                        <span style="font-size: 0.95rem; color: #555;">
                            Already have an account? <a href="/login" style="color: #4CAF50; text-decoration: none;">Login</a>
                        </span>
                        <button type="submit" 
                            style="background-color: #4CAF50; color: white; padding: 0.75rem 2.5rem; border: none; border-radius: 4px; font-size: 1rem; cursor: pointer; width: auto; min-width: 120px;">
                            Register
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</body>
</html>