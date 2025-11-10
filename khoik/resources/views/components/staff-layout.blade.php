<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title ?? 'GENZ EXPRESS - Hệ thống nhân viên' }}</title>
    
    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        primary: '#FF6B35',
                        'primary-dark': '#E55A2B',
                        secondary: '#003A70',
                        'secondary-dark': '#002850',
                    }
                }
            }
        }
    </script>
    
    <!-- Custom CSS -->
    <style type="text/tailwindcss">
        @layer components {
            .btn {
                @apply px-6 py-3 rounded-lg font-semibold transition-all duration-200 inline-flex items-center justify-center gap-2;
            }
            .btn-primary {
                @apply bg-orange-600 text-white hover:bg-orange-700 shadow-md hover:shadow-lg;
            }
            .btn-secondary {
                @apply bg-blue-900 text-white hover:bg-blue-950 shadow-md hover:shadow-lg;
            }
            .btn-outline {
                @apply border-2 border-orange-600 text-orange-600 hover:bg-orange-600 hover:text-white;
            }
            .btn-ghost {
                @apply text-orange-600 hover:bg-orange-50;
            }
            .btn-sm {
                @apply px-4 py-2 text-sm;
            }
            .btn-lg {
                @apply px-8 py-4 text-lg;
            }
            .card {
                @apply bg-white rounded-lg shadow-md hover:shadow-lg transition-shadow duration-200;
            }
            .badge {
                @apply px-3 py-1 rounded-full text-xs font-semibold inline-flex items-center gap-1;
            }
            .badge-pending {
                @apply bg-yellow-100 text-yellow-800;
            }
            .badge-confirmed {
                @apply bg-blue-100 text-blue-800;
            }
            .badge-picked_up {
                @apply bg-purple-100 text-purple-800;
            }
            .badge-in_transit {
                @apply bg-indigo-100 text-indigo-800;
            }
            .badge-out_delivery {
                @apply bg-orange-100 text-orange-800;
            }
            .badge-delivered {
                @apply bg-green-100 text-green-800;
            }
            .badge-cancelled {
                @apply bg-red-100 text-red-800;
            }
            .badge-returned {
                @apply bg-gray-100 text-gray-800;
            }
            .form-label {
                @apply block text-sm font-semibold text-gray-700 mb-2;
            }
            .form-input {
                @apply w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-orange-500 focus:border-transparent transition-all;
            }
            .form-select {
                @apply w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-orange-500 focus:border-transparent bg-white;
            }
            .navbar {
                @apply bg-blue-900 text-white shadow-lg;
            }
            .navbar-link {
                @apply text-white hover:text-orange-400 transition-colors duration-200 font-medium;
            }
            .table-wrapper {
                @apply bg-white rounded-lg shadow overflow-hidden;
            }
            .table {
                @apply min-w-full divide-y divide-gray-200;
            }
            .table-th {
                @apply px-6 py-4 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider bg-gray-50;
            }
            .table-td {
                @apply px-6 py-4 text-sm text-gray-900;
            }
            .table-row {
                @apply hover:bg-gray-50 transition-colors duration-150 border-b border-gray-100 last:border-0;
            }
            .alert {
                @apply px-6 py-4 rounded-lg mb-4 border-l-4;
            }
            .alert-success {
                @apply bg-green-50 border-green-500 text-green-800;
            }
            .alert-error {
                @apply bg-red-50 border-red-500 text-red-800;
            }
        }
    </style>
    
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Inter', sans-serif; }
    </style>
    
    {{ $head ?? '' }}
</head>
<body class="min-h-screen bg-gray-50">
    <!-- Navbar cho nhân viên -->
    <nav class="navbar">
        <div class="container mx-auto px-4 py-4">
            <div class="flex justify-between items-center">
                <!-- Logo -->
                <a href="{{ route('staff.dashboard') }}" class="flex items-center gap-3">
                    <div class="w-12 h-12 bg-orange-600 rounded-lg flex items-center justify-center">
                        <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                        </svg>
                    </div>
                    <div>
                        <h1 class="text-2xl font-bold">GENZ EXPRESS</h1>
                        <p class="text-xs text-orange-300">Hệ thống nhân viên</p>
                    </div>
                </a>
                
                <!-- Navigation cho nhân viên -->
                <div class="flex items-center gap-6">
                    <a href="{{ route('staff.orders.index') }}" class="navbar-link">Đơn được phân công</a>
                    <a href="{{ route('staff.incidents.index') }}" class="navbar-link">Báo cáo sự cố</a>
                    <a href="{{ route('staff.info.genz') }}" class="navbar-link">Giới thiệu GenZExpress</a>
                    <a href="{{ route('staff.info') }}" class="navbar-link">Thông tin</a>
                    
                    <!-- Notification bell -->
                    <div class="relative" id="notificationDropdown">
                        <button onclick="toggleNotificationDropdown()" class="relative hover:bg-blue-800 p-2 rounded-lg transition-colors">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>
                            </svg>
                            @if(auth()->user()->unreadNotifications()->where('type', 'new_order')->count() > 0)
                                <span class="absolute top-0 right-0 w-5 h-5 bg-red-500 text-white text-xs rounded-full flex items-center justify-center font-bold">
                                    {{ auth()->user()->unreadNotifications()->where('type', 'new_order')->count() }}
                                </span>
                            @endif
                        </button>
                        
                        <!-- Notification dropdown -->
                        <div class="absolute right-0 mt-2 w-80 bg-white rounded-lg shadow-lg hidden z-50" id="notificationMenu">
                            <div class="px-4 py-3 border-b border-gray-200 flex justify-between items-center">
                                <h3 class="font-semibold text-gray-900">Thông báo</h3>
                                @if(auth()->user()->unreadNotifications()->where('type', 'new_order')->count() > 0)
                                    <form action="{{ route('notifications.markAllAsRead') }}" method="POST">
                                        @csrf
                                        <button type="submit" class="text-xs text-orange-600 hover:text-orange-700">Đọc tất cả</button>
                                    </form>
                                @endif
                            </div>
                            <div class="max-h-96 overflow-y-auto">
                                @forelse(auth()->user()->notifications()->where('type', 'new_order')->take(5)->get() as $notification)
                                    <a href="{{ $notification->data['order_id'] ?? '#' ? route('staff.orders.status.edit', $notification->data['order_id']) : '#' }}" 
                                       class="block px-4 py-3 hover:bg-gray-50 transition-colors border-b border-gray-100 {{ $notification->isUnread() ? 'bg-blue-50' : '' }}">
                                        <div class="flex justify-between items-start mb-1">
                                            <p class="font-semibold text-sm text-gray-900">{{ $notification->title }}</p>
                                            @if($notification->isUnread())
                                                <span class="w-2 h-2 bg-orange-600 rounded-full"></span>
                                            @endif
                                        </div>
                                        <p class="text-xs text-gray-600">{{ $notification->message }}</p>
                                        <p class="text-xs text-gray-400 mt-1">{{ $notification->created_at->diffForHumans() }}</p>
                                    </a>
                                @empty
                                    <div class="px-4 py-8 text-center text-gray-500">
                                        <svg class="w-12 h-12 mx-auto mb-2 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>
                                        </svg>
                                        <p class="text-sm">Chưa có thông báo</p>
                                    </div>
                                @endforelse
                            </div>
                            @if(auth()->user()->notifications()->where('type', 'new_order')->count() > 0)
                                <div class="px-4 py-3 border-t border-gray-200 text-center">
                                    <a href="{{ route('notifications.index') }}" class="text-sm text-orange-600 hover:text-orange-700 font-semibold">Xem tất cả thông báo</a>
                                </div>
                            @endif
                        </div>
                    </div>
                    
                    <!-- User dropdown -->
                    <div class="relative" id="userDropdown">
                        <button onclick="toggleUserDropdown()" class="flex items-center gap-2 hover:bg-blue-800 px-3 py-2 rounded-lg transition-colors">
                            <div class="w-8 h-8 bg-orange-600 rounded-full flex items-center justify-center">
                                <span class="text-sm font-bold">{{ substr(auth()->user()->name, 0, 1) }}</span>
                            </div>
                            <div class="text-left hidden md:block">
                                <div class="text-sm font-semibold">{{ auth()->user()->name }}</div>
                                <div class="text-xs text-orange-300">Nhân viên vận chuyển</div>
                            </div>
                            <svg class="w-4 h-4 transition-transform" id="dropdownArrow" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                            </svg>
                        </button>
                        
                        <!-- Dropdown menu -->
                        <div class="absolute right-0 mt-2 w-48 bg-white rounded-lg shadow-lg py-2 hidden z-50" id="dropdownMenu">
                            <a href="{{ route('staff.orders.index') }}" class="block px-4 py-2 text-gray-800 hover:bg-orange-50 transition-colors">
                                Đơn được phân công
                            </a>
                            <a href="{{ route('staff.incidents.index') }}" class="block px-4 py-2 text-gray-800 hover:bg-orange-50 transition-colors">
                                Báo cáo sự cố
                            </a>
                            <a href="{{ route('staff.info.genz') }}" class="block px-4 py-2 text-gray-800 hover:bg-orange-50 transition-colors">
                                Giới thiệu GenZExpress
                            </a>
                            <a href="{{ route('staff.info') }}" class="block px-4 py-2 text-gray-800 hover:bg-orange-50 transition-colors">
                                Trung tâm thông tin
                            </a>
                            <hr class="my-2">
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="block w-full text-left px-4 py-2 text-red-600 hover:bg-red-50 transition-colors">
                                    Đăng xuất
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </nav>

    <!-- Alerts -->
    @if(session('success'))
        <div class="container mx-auto px-4 mt-4">
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        </div>
    @endif

    @if(session('error'))
        <div class="container mx-auto px-4 mt-4">
            <div class="alert alert-error">
                {{ session('error') }}
            </div>
        </div>
    @endif

    <!-- Main Content -->
    <main class="container mx-auto px-4 py-8">
        {{ $slot }}
    </main>

    <!-- Footer -->
    <footer class="bg-gray-900 text-white py-12 mt-16">
        <div class="container mx-auto px-4">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <div>
                    <h3 class="font-bold text-lg mb-4 text-orange-400">GENZ EXPRESS</h3>
                    <p class="text-gray-400 text-sm">Hệ thống quản lý dành cho nhân viên vận chuyển</p>
                </div>
                <div>
                    <h4 class="font-semibold mb-4">Hỗ trợ</h4>
                    <ul class="space-y-2 text-sm text-gray-400">
                        <li>Hotline: 1900-xxxx</li>
                        <li>Email: support@genzexpress.vn</li>
                    </ul>
                </div>
                <div>
                    <h4 class="font-semibold mb-4">Thông tin</h4>
                    <ul class="space-y-2 text-sm text-gray-400">
                        <li>Phiên bản: 1.0.0</li>
                        <li>© 2025 GENZ EXPRESS</li>
                    </ul>
                </div>
            </div>
        </div>
    </footer>

    <script>
        // Toggle user dropdown
        function toggleUserDropdown() {
            const dropdown = document.getElementById('dropdownMenu');
            const arrow = document.getElementById('dropdownArrow');
            dropdown.classList.toggle('hidden');
            arrow.classList.toggle('rotate-180');
            
            // Close notification dropdown if open
            document.getElementById('notificationMenu')?.classList.add('hidden');
        }

        // Toggle notification dropdown
        function toggleNotificationDropdown() {
            const dropdown = document.getElementById('notificationMenu');
            dropdown.classList.toggle('hidden');
            
            // Close user dropdown if open
            const userDropdown = document.getElementById('dropdownMenu');
            if (userDropdown) {
                userDropdown.classList.add('hidden');
                document.getElementById('dropdownArrow')?.classList.remove('rotate-180');
            }
        }

        // Close dropdowns when clicking outside
        document.addEventListener('click', function(event) {
            const userDropdown = document.getElementById('userDropdown');
            const dropdown = document.getElementById('dropdownMenu');
            const notificationDropdown = document.getElementById('notificationDropdown');
            const notificationMenu = document.getElementById('notificationMenu');
            
            if (userDropdown && !userDropdown.contains(event.target)) {
                dropdown?.classList.add('hidden');
                document.getElementById('dropdownArrow')?.classList.remove('rotate-180');
            }
            
            if (notificationDropdown && !notificationDropdown.contains(event.target)) {
                notificationMenu?.classList.add('hidden');
            }
        });
    </script>

    {{ $scripts ?? '' }}
</body>
</html>
