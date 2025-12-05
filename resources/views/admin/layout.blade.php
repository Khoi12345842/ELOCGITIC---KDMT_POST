<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Admin Panel')</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-50">
    <!-- Navbar -->
    <nav class="bg-gradient-to-r from-orange-500 to-orange-600 text-white shadow-lg">
        <div class="max-w-7xl mx-auto px-4">
            <div class="flex justify-between items-center h-16">
                <div class="flex items-center gap-8">
                    <h1 class="text-xl font-bold">ADMIN PANEL</h1>
                    <div class="hidden md:flex gap-6">
                        <a href="{{ route('admin.dashboard') }}" class="hover:text-orange-100 transition {{ request()->routeIs('admin.dashboard') ? 'text-white font-semibold' : '' }}">
                            Dashboard
                        </a>
                        <a href="{{ route('admin.users.index') }}" class="hover:text-orange-100 transition {{ request()->routeIs('admin.users.*') ? 'text-white font-semibold' : '' }}">
                            Quản lý người dùng
                        </a>
                        <a href="{{ route('admin.orders.index') }}" class="hover:text-orange-100 transition {{ request()->routeIs('admin.orders.*') ? 'text-white font-semibold' : '' }}">
                            Quản lý đơn hàng
                        </a>
                    </div>
                </div>

                <div class="flex items-center gap-4">
                    <span class="text-sm">{{ auth()->user()->name }}</span>
                    <form action="{{ route('admin.logout') }}" method="POST">
                        @csrf
                        <button type="submit" class="bg-orange-700 hover:bg-orange-800 px-4 py-2 rounded-lg text-sm transition">
                            Đăng xuất
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </nav>

    <!-- Content -->
    <main class="max-w-7xl mx-auto px-4 py-8">
        <!-- Flash Messages -->
        @if(session('success'))
            <div class="bg-green-50 border-l-4 border-green-500 text-green-800 p-4 rounded mb-6">
                <p class="font-semibold">{{ session('success') }}</p>
            </div>
        @endif

        @if(session('error'))
            <div class="bg-red-50 border-l-4 border-red-500 text-red-800 p-4 rounded mb-6">
                <p class="font-semibold">{{ session('error') }}</p>
            </div>
        @endif

        @if($errors->any())
            <div class="bg-red-50 border-l-4 border-red-500 text-red-800 p-4 rounded mb-6">
                <p class="font-semibold">Có lỗi xảy ra:</p>
                <ul class="list-disc list-inside mt-2">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        @yield('content')
    </main>

    <!-- Footer -->
    <footer class="bg-gray-800 text-white py-6 mt-12">
        <div class="max-w-7xl mx-auto px-4 text-center">
            <p>&copy; 2025 GENZ EXPRESS - Admin Panel</p>
        </div>
    </footer>
</body>
</html>
