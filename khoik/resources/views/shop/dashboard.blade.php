<x-layout title="Quản lý Shop - FastShip Business">
    <div class="max-w-7xl mx-auto">
        <!-- Shop Header -->
        <div class="bg-gradient-to-r from-orange-600 to-orange-500 text-white rounded-xl p-6 mb-6 shadow-lg">
            <div class="flex items-center justify-between flex-wrap gap-4">
                <div class="flex items-center gap-4">
                    <div class="w-16 h-16 bg-white rounded-lg flex items-center justify-center text-3xl">
                        @if($user->shop_platform === 'Shopee')
                            🛍️
                        @elseif($user->shop_platform === 'Lazada')
                            🏬
                        @elseif($user->shop_platform === 'TikTok Shop')
                            🎵
                        @else
                            🏪
                        @endif
                    </div>
                    <div>
                        <h1 class="text-2xl font-bold">{{ $user->shop_name }}</h1>
                        <div class="flex items-center gap-3 mt-1 text-orange-100">
                            <span class="text-sm">{{ $user->shop_platform }}</span>
                            <span class="text-sm">•</span>
                            <span class="text-sm">ID: {{ $user->shop_id }}</span>
                            <span class="text-sm">•</span>
                            <span class="text-sm">Giảm {{ $user->discount_rate }}%</span>
                        </div>
                    </div>
                </div>

                <div class="text-right">
                    <p class="text-sm text-orange-100">Hợp đồng còn hiệu lực</p>
                    <p class="text-xl font-bold">
                        {{ \Carbon\Carbon::parse($user->contract_end_date)->diffInDays(now()) }} ngày
                    </p>
                    <p class="text-xs text-orange-100">
                        Đến {{ \Carbon\Carbon::parse($user->contract_end_date)->format('d/m/Y') }}
                    </p>
                </div>
            </div>
        </div>

        <!-- Stats Cards -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
            <x-card class="text-center">
                <div class="text-3xl font-bold text-orange-600">{{ $stats['total_orders'] }}</div>
                <div class="text-sm text-gray-600 mt-1">Tổng đơn hàng</div>
                <div class="text-xs text-gray-500 mt-1">Từ shop</div>
            </x-card>

            <x-card class="text-center bg-yellow-50 border-yellow-200">
                <div class="text-3xl font-bold text-yellow-600">{{ $stats['pending'] }}</div>
                <div class="text-sm text-gray-600 mt-1">Chờ lấy hàng</div>
                <div class="text-xs text-gray-500 mt-1">⏳ Pending</div>
            </x-card>

            <x-card class="text-center bg-blue-50 border-blue-200">
                <div class="text-3xl font-bold text-blue-600">{{ $stats['in_transit'] }}</div>
                <div class="text-sm text-gray-600 mt-1">Đang vận chuyển</div>
                <div class="text-xs text-gray-500 mt-1">🚚 In Transit</div>
            </x-card>

            <x-card class="text-center bg-green-50 border-green-200">
                <div class="text-3xl font-bold text-green-600">{{ $stats['delivered'] }}</div>
                <div class="text-sm text-gray-600 mt-1">Đã giao hàng</div>
                <div class="text-xs text-gray-500 mt-1">✅ Delivered</div>
            </x-card>
        </div>

        <!-- Actions -->
        <div class="flex gap-4 mb-6">
            <form method="POST" action="{{ route('shop.sync') }}" class="flex-1">
                @csrf
                <button type="submit" class="btn btn-primary btn-lg w-full">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                    </svg>
                    Đồng bộ đơn mới từ Shop
                </button>
            </form>

            <a href="{{ route('orders.index') }}" class="btn btn-outline btn-lg">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                </svg>
                Tất cả đơn hàng
            </a>
        </div>

        <!-- Orders Table -->
        <x-card>
            <x-slot:header>
                <div class="flex items-center justify-between">
                    <h3 class="text-xl font-bold">📦 Đơn hàng từ Shop</h3>
                    <span class="text-sm text-gray-500">Cập nhật: {{ now()->format('H:i d/m/Y') }}</span>
                </div>
            </x-slot:header>

            @if($orders->isEmpty())
                <div class="text-center py-12">
                    <div class="text-6xl mb-4">📭</div>
                    <p class="text-gray-600 text-lg">Chưa có đơn hàng nào</p>
                    <p class="text-gray-500 text-sm mt-2">
                        Nhấn "Đồng bộ đơn mới" để tải đơn hàng từ shop của bạn
                    </p>
                </div>
            @else
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead class="bg-gray-50 border-b-2 border-gray-200">
                            <tr>
                                <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase">Mã đơn Shop</th>
                                <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase">Mã vận đơn</th>
                                <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase">Người nhận</th>
                                <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase">Điểm đến</th>
                                <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase">Trạng thái</th>
                                <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase">Phí VC</th>
                                <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase">Ngày tạo</th>
                                <th class="px-4 py-3 text-center text-xs font-semibold text-gray-600 uppercase">Thao tác</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200">
                            @foreach($orders as $order)
                            <tr class="hover:bg-gray-50 transition">
                                <td class="px-4 py-3">
                                    <div class="font-mono text-sm font-semibold text-orange-600">
                                        {{ $order->shop_order_id }}
                                    </div>
                                    <div class="text-xs text-gray-500">{{ $order->shop_platform }}</div>
                                </td>
                                <td class="px-4 py-3">
                                    <div class="font-mono text-sm">{{ $order->tracking_number }}</div>
                                    <div class="text-xs text-gray-500">{{ $order->order_number }}</div>
                                </td>
                                <td class="px-4 py-3">
                                    <div class="font-medium">{{ $order->receiver_name }}</div>
                                    <div class="text-xs text-gray-500">{{ $order->receiver_phone }}</div>
                                </td>
                                <td class="px-4 py-3">
                                    <div class="text-sm">{{ $order->receiver_city }}</div>
                                    <div class="text-xs text-gray-500">{{ $order->receiver_district }}</div>
                                </td>
                                <td class="px-4 py-3">
                                    @php
                                        $statusConfig = [
                                            'pending' => ['label' => 'Chờ lấy', 'color' => 'yellow'],
                                            'picked_up' => ['label' => 'Đã lấy', 'color' => 'blue'],
                                            'in_transit' => ['label' => 'Đang giao', 'color' => 'indigo'],
                                            'delivered' => ['label' => 'Đã giao', 'color' => 'green'],
                                            'failed' => ['label' => 'Thất bại', 'color' => 'red'],
                                            'returned' => ['label' => 'Hoàn', 'color' => 'gray'],
                                        ][$order->status] ?? ['label' => $order->status, 'color' => 'gray'];
                                    @endphp
                                    <span class="px-2 py-1 text-xs font-semibold rounded-full bg-{{ $statusConfig['color'] }}-100 text-{{ $statusConfig['color'] }}-800">
                                        {{ $statusConfig['label'] }}
                                    </span>
                                </td>
                                <td class="px-4 py-3">
                                    <div class="font-semibold text-sm">{{ number_format($order->shipping_fee) }}đ</div>
                                    @if($user->discount_rate > 0)
                                        <div class="text-xs text-green-600">
                                            -{{ $user->discount_rate }}%
                                        </div>
                                    @endif
                                </td>
                                <td class="px-4 py-3 text-sm text-gray-600">
                                    {{ $order->created_at->format('d/m/Y H:i') }}
                                </td>
                                <td class="px-4 py-3 text-center">
                                    <a href="{{ route('orders.show', $order) }}" 
                                       class="inline-flex items-center gap-1 text-orange-600 hover:text-orange-700 font-medium text-sm">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                        </svg>
                                        Chi tiết
                                    </a>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                @if($orders->hasPages())
                <div class="mt-6">
                    {{ $orders->links() }}
                </div>
                @endif
            @endif
        </x-card>

        <!-- Contract Info -->
        <x-card class="mt-6 bg-blue-50 border-blue-200">
            <div class="flex items-start gap-4">
                <div class="text-3xl">📋</div>
                <div class="flex-1">
                    <h4 class="font-bold text-lg mb-2">Thông tin hợp đồng</h4>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 text-sm">
                        <div>
                            <span class="text-gray-600">Ngày ký:</span>
                            <span class="font-semibold ml-2">
                                {{ \Carbon\Carbon::parse($user->contract_start_date)->format('d/m/Y') }}
                            </span>
                        </div>
                        <div>
                            <span class="text-gray-600">Ngày hết hạn:</span>
                            <span class="font-semibold ml-2">
                                {{ \Carbon\Carbon::parse($user->contract_end_date)->format('d/m/Y') }}
                            </span>
                        </div>
                        <div>
                            <span class="text-gray-600">Mức giảm giá:</span>
                            <span class="font-semibold ml-2 text-green-600">
                                {{ $user->discount_rate }}%
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </x-card>
    </div>
</x-layout>
