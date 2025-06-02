<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title')</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <style>
        body {
            font-family: sans-serif;
            margin: 0;
            background-color: #f8f8f8;
        }

        .navbar {
            background: #00674F;
            padding: 15px 20px;
            width: 100%;
            box-sizing: border-box;
        }

        .navbar ul {
            list-style: none;
            margin: 0;
            padding: 0;
            display: flex;
            gap: 20px;
            justify-content: center;
            align-items: center;
        }

        .navbar li {
            display: flex;
            align-items: center;
        }

        .navbar a {
            color: #fff;
            text-decoration: none;
            font-weight: bold;
            padding: 8px 16px;
            border-radius: 4px;
            transition: background 0.2s;
        }

        .navbar a:hover,
        .navbar a.active {
            background: #113d33;
        }

        .nav-link-button {
            color: #fff;
            background: none;
            border: none;
            font-weight: bold;
            padding: 8px 16px;
            border-radius: 4px;
            cursor: pointer;
            font-family: inherit;
            font-size: inherit;
            transition: background 0.2s;
            display: flex;
            align-items: center;
            text-decoration: none;
        }

        .nav-link-button:hover {
            background: #113d33;
        }
    </style>
</head>
<body>
    <nav class="navbar">
        <ul>
            <li><a href="{{ route('admin.dashboard') }}" class="{{ request()->routeIs('admin.dashboard') ? 'active' : '' }}"><i class="fa fa-tachometer-alt"></i> Dashboard</a></li>
            <li><a href="{{ route('admin.product_management') }}" class="{{ request()->routeIs('admin.product_management') ? 'active' : '' }}"><i class="fa fa-box"></i> Products</a></li>
            <li><a href="{{ route('admin.category_management') }}" class="{{ request()->routeIs('admin.category_management') ? 'active' : '' }}"><i class="fa fa-list"></i> Category</a></li>
            <li><a href="{{ route('admin.orders_management') }}" class="{{ request()->routeIs('admin.orders_management') ? 'active' : '' }}"><i class="fa fa-shopping-cart"></i> Orders</a></li>
            <li><a href="{{ route('admin.users_management') }}"><i class="fa fa-users"></i> Users</a></li>
            <li><a href="#"><i class="fa fa-chart-bar"></i> Reports</a></li>
            <li><a href="#"><i class="fa fa-tags"></i> Discounts</a></li>
            <li>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="nav-link-button">
                        <i class="fa fa-sign-out-alt"></i> Logout
                    </button>
                </form>
            </li>
        </ul>
    </nav>

    <div class="content">
        @yield('content')
    </div>
</body>
</html>
