<x-layout title="Quản lý đơn hàng - FastShip Business">
    <div class="max-w-6xl mx-auto">
        <x-page-header 
            title="📊 Quản lý đơn hàng doanh nghiệp" 
            subtitle="Liên kết shop để tự động đồng bộ đơn hoặc tạo đơn lẻ"
        />

        <!-- Thông tin ưu đãi -->
        <div class="bg-gradient-to-r from-blue-50 to-blue-100 border-l-4 border-blue-500 p-6 rounded-lg mb-6">
            <div class="flex items-start gap-4">
                <div class="text-4xl">🏢</div>
                <div class="flex-1">
                    <h3 class="font-bold text-lg text-gray-900 mb-2">Ưu đãi doanh nghiệp của bạn</h3>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 text-sm">
                        <div class="flex items-center gap-2">
                            <span class="text-2xl">💰</span>
                            <div>
                                <div class="font-semibold">Giảm giá vận chuyển</div>
                                <div class="text-blue-700">{{ auth()->user()->discount_rate }}%</div>
                            </div>
                        </div>
                        <div class="flex items-center gap-2">
                            <span class="text-2xl">🏪</span>
                            <div>
                                <div class="font-semibold">Shop liên kết</div>
                                <div class="text-blue-700">{{ auth()->user()->shop_name ?? 'Chưa liên kết' }}</div>
                            </div>
                        </div>
                        <div class="flex items-center gap-2">
                            <span class="text-2xl">📄</span>
                            <div>
                                <div class="font-semibold">Hợp đồng</div>
                                <div class="text-blue-700">{{ auth()->user()->has_contract ? 'Đang hiệu lực' : 'Chưa có' }}</div>
                            </div>
                        </div>
                    </div>

                    <!-- Nút liên kết shop nếu chưa liên kết -->
                    @if(!auth()->user()->has_contract || !auth()->user()->shop_id)
                        <div class="mt-4 pt-4 border-t border-blue-200">
                            <p class="text-sm text-gray-700 mb-3">
                                💡 <strong>Liên kết shop của bạn</strong> để tự động đồng bộ đơn hàng và hưởng thêm ưu đãi!
                            </p>
                            <a href="{{ route('shop.link') }}" class="btn btn-primary btn-sm inline-flex items-center gap-2">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1"/>
                                </svg>
                                Liên kết Shop ngay
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Đơn hàng từ Shop (nếu có) -->
        @if($shopOrderCount > 0)
        <div class="mt-8">
            <x-card>
                <x-slot:header>
                    <div class="flex items-center justify-between">
                        <div class="flex items-center gap-3">
                            <div class="bg-gradient-to-br from-green-500 to-teal-600 text-white p-3 rounded-xl">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/>
                                </svg>
                            </div>
                            <div>
                                <h3 class="text-lg font-bold">🏪 Đơn hàng từ Shop của bạn</h3>
                                <p class="text-sm text-gray-600">Đã đồng bộ {{ $shopOrderCount }} đơn hàng từ shop liên kết</p>
                            </div>
                        </div>
                        <a href="{{ route('shop.dashboard') }}" class="btn btn-secondary">
                            Xem tất cả →
                        </a>
                    </div>
                </x-slot:header>

                <!-- 10 đơn gần nhất -->
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Mã đơn</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Người nhận</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Địa chỉ</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Trạng thái</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Ngày tạo</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Thao tác</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach($shopOrders as $order)
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="text-sm font-medium text-gray-900">{{ $order->tracking_number }}</span>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="text-sm">
                                        <div class="font-medium text-gray-900">{{ $order->receiver_name }}</div>
                                        <div class="text-gray-500">{{ $order->receiver_phone }}</div>
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="text-sm text-gray-900">{{ $order->receiver_city }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @php
                                        $statusConfig = [
                                            'pending' => ['bg' => 'bg-yellow-100', 'text' => 'text-yellow-800', 'label' => '⏳ Chờ lấy hàng'],
                                            'picked_up' => ['bg' => 'bg-blue-100', 'text' => 'text-blue-800', 'label' => '📦 Đã lấy hàng'],
                                            'in_transit' => ['bg' => 'bg-purple-100', 'text' => 'text-purple-800', 'label' => '🚚 Đang vận chuyển'],
                                            'out_for_delivery' => ['bg' => 'bg-indigo-100', 'text' => 'text-indigo-800', 'label' => '🏃 Đang giao'],
                                            'delivered' => ['bg' => 'bg-green-100', 'text' => 'text-green-800', 'label' => '✅ Đã giao'],
                                            'failed' => ['bg' => 'bg-red-100', 'text' => 'text-red-800', 'label' => '❌ Giao thất bại'],
                                            'returned' => ['bg' => 'bg-gray-100', 'text' => 'text-gray-800', 'label' => '↩️ Hoàn trả'],
                                        ];
                                        $config = $statusConfig[$order->status] ?? ['bg' => 'bg-gray-100', 'text' => 'text-gray-800', 'label' => $order->status];
                                    @endphp
                                    <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full {{ $config['bg'] }} {{ $config['text'] }}">
                                        {{ $config['label'] }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    {{ $order->created_at->format('d/m/Y H:i') }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm">
                                    <a href="{{ route('orders.track') }}?tracking_number={{ $order->tracking_number }}" 
                                       class="text-orange-600 hover:text-orange-800 font-medium">
                                        Chi tiết →
                                    </a>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                @if($shopOrderCount > 10)
                <div class="mt-4 text-center text-sm text-gray-600 bg-gray-50 py-3 rounded-lg">
                    Hiển thị 10 đơn gần nhất. 
                    <a href="{{ route('shop.dashboard') }}" class="text-orange-600 hover:text-orange-800 font-semibold">
                        Xem {{ $shopOrderCount - 10 }} đơn còn lại →
                    </a>
                </div>
                @endif
            </x-card>
        </div>
        @endif

        <!-- Chức năng chính -->
        <div class="grid md:grid-cols-2 gap-6 mt-8">
            <!-- Liên kết Shop -->
            <x-card>
                <x-slot:header>
                    <div class="flex items-center gap-3">
                        <div class="bg-gradient-to-br from-blue-500 to-indigo-600 text-white p-3 rounded-xl">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1"/>
                            </svg>
                        </div>
                        <h3 class="text-lg font-bold">🏪 Liên kết Shop</h3>
                    </div>
                </x-slot:header>

                <div class="space-y-4">
                    <p class="text-gray-600">Kết nối với Shopee, Lazada, TikTok Shop để tự động đồng bộ đơn hàng</p>
                    
                    <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                        <h4 class="font-semibold text-blue-900 mb-2">✨ Lợi ích khi liên kết:</h4>
                        <ul class="text-sm text-blue-800 space-y-1">
                            <li>✅ Tự động đồng bộ đơn hàng từ shop</li>
                            <li>✅ Không cần nhập thủ công</li>
                            <li>✅ Cập nhật trạng thái real-time</li>
                            <li>✅ Giảm giá vận chuyển đến {{ auth()->user()->discount_rate }}%</li>
                        </ul>
                    </div>

                    @if(auth()->user()->has_contract && auth()->user()->shop_id)
                        <div class="bg-green-50 border border-green-200 rounded-lg p-4 text-center">
                            <div class="text-4xl mb-2">✅</div>
                            <p class="font-semibold text-green-900">Đã liên kết: {{ auth()->user()->shop_name }}</p>
                            <a href="{{ route('shop.dashboard') }}" class="btn btn-secondary btn-sm mt-3">
                                Quản lý Shop →
                            </a>
                        </div>
                    @else
                        <a href="{{ route('shop.link') }}" class="btn btn-primary w-full btn-lg">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1"/>
                            </svg>
                            Liên kết Shop ngay
                        </a>
                    @endif
                </div>
            </x-card>

            <!-- Tạo đơn lẻ -->
            <x-card>
                <x-slot:header>
                    <div class="flex items-center gap-3">
                        <div class="bg-gradient-to-br from-orange-500 to-red-600 text-white p-3 rounded-xl">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                            </svg>
                        </div>
                        <h3 class="text-lg font-bold">📦 Tạo đơn lẻ</h3>
                    </div>
                </x-slot:header>

                <div class="space-y-4">
                    <p class="text-gray-600">Tạo đơn hàng riêng lẻ giống như khách hàng cá nhân</p>
                    
                    <div class="bg-orange-50 border border-orange-200 rounded-lg p-4">
                        <h4 class="font-semibold text-orange-900 mb-2">📋 Phù hợp cho:</h4>
                        <ul class="text-sm text-orange-800 space-y-1">
                            <li>✓ Đơn hàng đặc biệt, khẩn cấp</li>
                            <li>✓ Đơn không từ sàn TMĐT</li>
                            <li>✓ Gửi hàng nội bộ công ty</li>
                            <li>✓ Đơn hàng thử nghiệm</li>
                        </ul>
                    </div>

                    <a href="{{ route('orders.create.individual') }}" class="btn btn-primary w-full btn-lg">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                        </svg>
                        Tạo đơn lẻ
                    </a>
                </div>
            </x-card>
        </div>
    </div>

    <!-- Popup liên kết thành công -->
    @if(session('shop_linked'))
    <div id="successPopup" class="fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center">
        <div class="bg-white rounded-2xl shadow-2xl max-w-lg w-full mx-4 overflow-hidden animate-bounce-in">
            <!-- Header -->
            <div class="bg-gradient-to-r from-green-500 to-emerald-600 text-white p-6 text-center">
                <div class="text-6xl mb-3">🎉</div>
                <h2 class="text-2xl font-bold">Liên kết thành công!</h2>
            </div>

            <!-- Content -->
            <div class="p-6 space-y-4">
                <div class="text-center">
                    <p class="text-lg text-gray-800 font-semibold mb-2">
                        Shop "{{ session('shop_name') }}" đã được liên kết
                    </p>
                    <p class="text-gray-600">
                        Nền tảng: <span class="font-semibold text-blue-600">{{ session('shop_platform') }}</span>
                    </p>
                </div>

                <div class="bg-blue-50 border-l-4 border-blue-500 p-4 rounded">
                    <div class="flex items-center gap-3">
                        <div class="text-3xl">📦</div>
                        <div>
                            <p class="font-semibold text-blue-900 mb-1">Đơn hàng đã được đồng bộ</p>
                            <p class="text-blue-800">
                                Hệ thống đã tự động tạo <span class="font-bold text-2xl">{{ session('order_count') }}</span> đơn hàng từ shop của bạn
                            </p>
                        </div>
                    </div>
                </div>

                <div class="bg-green-50 border-l-4 border-green-500 p-4 rounded">
                    <div class="flex items-center gap-3">
                        <div class="text-3xl">👨‍💼</div>
                        <div>
                            <p class="font-semibold text-green-900 mb-1">Nhân viên đã được thông báo</p>
                            <p class="text-green-800">
                                Các đơn hàng đã được phân công tự động cho nhân viên vận chuyển
                            </p>
                        </div>
                    </div>
                </div>

                <div class="bg-yellow-50 border border-yellow-300 p-4 rounded-lg">
                    <p class="text-sm text-yellow-800">
                        💡 <strong>Mẹo:</strong> Các đơn hàng sẽ được cập nhật trạng thái tự động. 
                        Bạn có thể xem chi tiết tại <strong>"Đơn hàng của tôi"</strong> hoặc <strong>"Quản lý Shop"</strong>
                    </p>
                </div>
            </div>

            <!-- Footer -->
            <div class="bg-gray-50 px-6 py-4 flex gap-3">
                <button onclick="closePopup()" class="flex-1 bg-orange-600 text-white px-6 py-3 rounded-lg font-semibold hover:bg-orange-700 transition">
                    Đóng
                </button>
                <a href="{{ route('orders.index') }}" class="flex-1 bg-blue-600 text-white px-6 py-3 rounded-lg font-semibold hover:bg-blue-700 transition text-center">
                    Xem đơn hàng
                </a>
            </div>
        </div>
    </div>

    <style>
        @keyframes bounce-in {
            0% {
                transform: scale(0.3);
                opacity: 0;
            }
            50% {
                transform: scale(1.05);
            }
            70% {
                transform: scale(0.9);
            }
            100% {
                transform: scale(1);
                opacity: 1;
            }
        }
        .animate-bounce-in {
            animation: bounce-in 0.6s ease-out;
        }
    </style>

    <script>
        function closePopup() {
            document.getElementById('successPopup').style.display = 'none';
        }
    </script>
    @endif
</x-layout>
