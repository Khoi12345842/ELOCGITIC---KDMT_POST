<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title ?? 'FastShip Logistics - Vận chuyển nhanh chóng, uy tín' }}</title>
    
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
            .card-header {
                @apply p-6 border-b border-gray-200;
            }
            .card-body {
                @apply p-6;
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
            .page-title {
                @apply text-3xl font-bold text-gray-900 mb-2;
            }
            .page-subtitle {
                @apply text-gray-600;
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
            .timeline-marker-active {
                @apply w-4 h-4 bg-orange-600 rounded-full mt-1.5 ring-4 ring-white;
            }
            .timeline-marker-inactive {
                @apply w-4 h-4 bg-gray-400 rounded-full mt-1.5 ring-4 ring-white;
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
            .info-box {
                @apply p-4 rounded-lg border;
            }
            .info-box-primary {
                @apply bg-orange-50 border-orange-200;
            }
            .info-box-success {
                @apply bg-green-50 border-green-200;
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
    <!-- Navbar -->
    <nav class="navbar">
        <div class="container mx-auto px-4 py-4">
            <div class="flex justify-between items-center">
                <!-- Logo -->
                <a href="/" class="flex items-center gap-3">
                    <div class="w-12 h-12 bg-orange-600 rounded-lg flex items-center justify-center">
                        <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                        </svg>
                    </div>
                    <div>
                        <h1 class="text-2xl font-bold">FastShip</h1>
                        <p class="text-xs text-orange-300">Giao hàng tận tâm</p>
                    </div>
                </a>
                
                <!-- Navigation -->
                <div class="flex items-center gap-6">
                    <a href="{{ route('orders.track') }}" class="navbar-link">
                        <svg class="w-5 h-5 inline-block" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                        </svg>
                        Tra cứu
                    </a>
                    <a href="{{ route('post-offices.index') }}" class="navbar-link">
                        <svg class="w-5 h-5 inline-block" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                        </svg>
                        Bưu cục
                    </a>
                    <a href="{{ route('orders.estimate') }}" class="navbar-link">
                        <svg class="w-5 h-5 inline-block" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z"/>
                        </svg>
                        Tính cước
                    </a>
                    <a href="{{ route('orders.index') }}" class="navbar-link">
                        <svg class="w-5 h-5 inline-block" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"/>
                        </svg>
                        Đơn hàng
                    </a>
                    <a href="{{ route('support.index') }}" class="navbar-link">
                        <svg class="w-5 h-5 inline-block" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 5.636l-3.536 3.536m0 5.656l3.536 3.536M9.172 9.172L5.636 5.636m3.536 9.192l-3.536 3.536M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-5 0a4 4 0 11-8 0 4 4 0 018 0z"/>
                        </svg>
                        Hỗ trợ
                    </a>
                    
                    @guest
                        <a href="{{ route('login') }}" class="btn btn-outline btn-sm border-white text-white hover:bg-white hover:text-blue-900">
                            Đăng nhập
                        </a>
                    @else
                        <!-- Nút Tạo đơn -->
                        @if(auth()->user()->isBusiness())
                            <a href="{{ route('orders.create.bulk') }}" class="btn btn-sm bg-orange-600 text-white hover:bg-orange-700">
                                ➕ Lên đơn theo lô
                            </a>
                        @else
                            <a href="{{ route('orders.create.individual') }}" class="btn btn-sm bg-orange-600 text-white hover:bg-orange-700">
                                ➕ Tạo đơn
                            </a>
                        @endif

                        <!-- Notification bell -->
                        <div class="relative" id="notificationDropdown">
                            <button onclick="toggleNotificationDropdown()" class="relative hover:bg-blue-800 p-2 rounded-lg transition-colors">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>
                                </svg>
                                @if(auth()->user()->unreadNotifications()->where('type', 'status_update')->count() > 0)
                                    <span class="absolute top-0 right-0 w-5 h-5 bg-red-500 text-white text-xs rounded-full flex items-center justify-center font-bold">
                                        {{ auth()->user()->unreadNotifications()->where('type', 'status_update')->count() }}
                                    </span>
                                @endif
                            </button>
                            
                            <!-- Notification dropdown -->
                            <div class="absolute right-0 mt-2 w-80 bg-white rounded-lg shadow-lg hidden z-50" id="notificationMenu">
                                <div class="px-4 py-3 border-b border-gray-200 flex justify-between items-center">
                                    <h3 class="font-semibold text-gray-900">Thông báo</h3>
                                    @if(auth()->user()->unreadNotifications()->where('type', 'status_update')->count() > 0)
                                        <form action="{{ route('notifications.markAllAsRead') }}" method="POST">
                                            @csrf
                                            <button type="submit" class="text-xs text-orange-600 hover:text-orange-700">Đọc tất cả</button>
                                        </form>
                                    @endif
                                </div>
                                <div class="max-h-96 overflow-y-auto">
                                    @forelse(auth()->user()->notifications()->where('type', 'status_update')->take(5)->get() as $notification)
                                        <a href="{{ $notification->data['order_id'] ? route('orders.show', $notification->data['order_id']) : '#' }}" 
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
                                @if(auth()->user()->notifications()->where('type', 'status_update')->count() > 0)
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
                                    <div class="text-xs text-orange-300">
                                        {{ auth()->user()->isBusiness() ? '🏢 Doanh nghiệp' : '👤 Cá nhân' }}
                                    </div>
                                </div>
                                <svg class="w-4 h-4 transition-transform" id="dropdownArrow" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                                </svg>
                            </button>
                            
                            <!-- Dropdown menu -->
                            <div class="absolute right-0 mt-2 w-48 bg-white rounded-lg shadow-lg py-2 hidden z-50" id="dropdownMenu">
                                <a href="{{ route('orders.index') }}" class="block px-4 py-2 text-gray-800 hover:bg-orange-50 transition-colors">
                                    📦 Đơn hàng của tôi
                                </a>
                                @if(auth()->user()->isBusiness())
                                    <a href="{{ route('orders.create.bulk') }}" class="block px-4 py-2 text-gray-800 hover:bg-orange-50 transition-colors">
                                        📊 Lên đơn theo lô
                                    </a>
                                    @if(auth()->user()->has_contract && auth()->user()->shop_id)
                                        <a href="{{ route('shop.dashboard') }}" class="block px-4 py-2 text-gray-800 hover:bg-orange-50 transition-colors">
                                            🏪 Quản lý Shop
                                        </a>
                                    @else
                                        <a href="{{ route('shop.link') }}" class="block px-4 py-2 text-gray-800 hover:bg-orange-50 transition-colors">
                                            🔗 Liên kết Shop
                                        </a>
                                    @endif
                                @else
                                    <a href="{{ route('orders.create.individual') }}" class="block px-4 py-2 text-gray-800 hover:bg-orange-50 transition-colors">
                                        ➕ Tạo đơn mới
                                    </a>
                                @endif
                                <hr class="my-2">
                                @if(!auth()->user()->isStaff())
                                    <a href="{{ route('settings.index') }}" class="block px-4 py-2 text-gray-800 hover:bg-orange-50 transition-colors">
                                        ⚙️ Cài đặt
                                    </a>
                                @endif
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit" class="block w-full text-left px-4 py-2 text-red-600 hover:bg-red-50 transition-colors">
                                        🚪 Đăng xuất
                                    </button>
                                </form>
                            </div>
                        </div>
                    @endguest
                </div>
            </div>
        </div>
    </nav>

    <!-- Alerts -->
    @if(session('success'))
        <div class="container mx-auto px-4 mt-4">
            <div class="alert alert-success">
                <svg class="w-5 h-5 inline-block" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                {{ session('success') }}
            </div>
        </div>
    @endif

    @if(session('error'))
        <div class="container mx-auto px-4 mt-4">
            <div class="alert alert-error">
                <svg class="w-5 h-5 inline-block" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
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
            <div class="grid grid-cols-1 md:grid-cols-4 gap-8">
                <div>
                    <h3 class="font-bold text-lg mb-4 text-orange-400">FastShip Logistics</h3>
                    <p class="text-gray-400 text-sm">Dịch vụ vận chuyển hàng đầu Việt Nam. Nhanh chóng - Uy tín - An toàn.</p>
                </div>
                <div>
                    <h4 class="font-semibold mb-4">Dịch vụ</h4>
                    <ul class="space-y-2 text-sm text-gray-400">
                        <li><a href="#" class="hover:text-orange-400">Giao hàng nội thành</a></li>
                        <li><a href="#" class="hover:text-orange-400">Giao hàng liên tỉnh</a></li>
                        <li><a href="#" class="hover:text-orange-400">Giao hàng quốc tế</a></li>
                        <li><a href="#" class="hover:text-orange-400">Giao hàng hỏa tốc</a></li>
                    </ul>
                </div>
                <div>
                    <h4 class="font-semibold mb-4">Hỗ trợ</h4>
                    <ul class="space-y-2 text-sm text-gray-400">
                        <li><a href="{{ route('orders.track') }}" class="hover:text-orange-400">Tra cứu đơn hàng</a></li>
                        <li><a href="{{ route('orders.estimate') }}" class="hover:text-orange-400">Tính cước phí</a></li>
                        <li><a href="{{ route('support.faq') }}" class="hover:text-orange-400">Câu hỏi thường gặp</a></li>
                        <li><a href="{{ route('support.contact') }}" class="hover:text-orange-400">Liên hệ</a></li>
                    </ul>
                </div>
                <div>
                    <h4 class="font-semibold mb-4">Liên hệ</h4>
                    <ul class="space-y-2 text-sm text-gray-400">
                        <li>📞 Hotline: 1900-xxxx</li>
                        <li>✉️ Email: support@fastship.vn</li>
                        <li>📍 Hà Nội, Việt Nam</li>
                    </ul>
                </div>
            </div>
            <div class="border-t border-gray-800 mt-8 pt-8 text-center text-sm text-gray-500">
                <p>&copy; 2025 FastShip Logistics. Demo System with Fake Data.</p>
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