<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'StockFlow - Stock Management')</title>
    
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <!-- Custom CSS -->
    <link href="{{ asset('css/style.css') }}" rel="stylesheet">
    
    @stack('styles')
</head>
<body>

<div class="dashboard-wrapper">
    
    <!-- Sidebar -->
    <aside class="sidebar">
        <div class="sidebar-header">
            <h3>Stock<span>Flow</span></h3>
        </div>
        
        <nav class="sidebar-nav">
            <a href="{{ route('dashboard') }}" class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                <span class="nav-icon">📊</span> Dashboard
            </a>
            <a href="{{ route('products.index') }}" class="nav-link {{ request()->routeIs('products.*') ? 'active' : '' }}">
                <span class="nav-icon">📦</span> Products
            </a>
            <a href="#" class="nav-link">
                <span class="nav-icon">📋</span> Orders
            </a>
            <a href="#" class="nav-link">
                <span class="nav-icon">👥</span> Customers
            </a>
            <a href="#" class="nav-link">
                <span class="nav-icon">⚙️</span> Settings
            </a>
        </nav>
        
        <div class="sidebar-footer">
            <a href="#" class="logout-link">
                <span>🚪</span> Logout
            </a>
        </div>
    </aside>

    <!-- Main Content -->
    <main class="main-content">
        
        <!-- Top Navbar -->
        <header class="top-navbar">
            <div class="navbar-left">
                <button class="mobile-toggle" id="mobileToggle">☰</button>
                <h4>@yield('page-title')</h4>
            </div>
            
            <div class="navbar-right">
                {{-- <div class="search-box">
                    <span>🔍</span>
                    <input type="text" placeholder="Search products...">
                </div>
                <div class="user-profile">
                    <div class="avatar">AD</div>
                    <span class="username">Admin</span>
                </div> --}}
            </div>
        </header>

        <!-- Page Content -->
        <div class="page-content">
            @yield('content')
        </div>

    </main>
</div>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<!-- Custom JS -->
<script src="{{ asset('js/app.js') }}"></script>
@stack('scripts')

</body>
</html>