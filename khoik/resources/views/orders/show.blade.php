<x-layout :title="'Chi tiết đơn hàng #' . $order->order_number">
    <div class="mb-6">
        <x-button :href="route('orders.index')" variant="ghost" size="sm">
            ← Quay lại danh sách
        </x-button>
    </div>

    @if(session('success'))
        <div class="mb-6 bg-green-50 border border-green-200 text-green-800 px-6 py-4 rounded-lg flex items-center gap-3 shadow-sm">
            <svg class="w-6 h-6 text-green-500 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
            </svg>
            <div>
                <p class="font-semibold">{{ session('success') }}</p>
                <p class="text-sm text-green-700 mt-1">Mã vận đơn: <span class="font-mono font-bold">{{ $order->shipment->tracking_number ?? 'Đang cập nhật' }}</span></p>
            </div>
        </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Thông tin đơn hàng -->
        <x-card>
            <x-slot:header>
                <h3 class="text-xl font-bold">Thông tin đơn hàng</h3>
            </x-slot:header>
            
            <div class="space-y-3">
                <div class="flex justify-between">
                    <span class="text-gray-600">Mã đơn hàng:</span>
                    <span class="font-mono font-bold text-orange-600">{{ $order->order_number }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-gray-600">Trạng thái:</span>
                    <x-order-status-badge :status="$order->status" />
                </div>
                <div class="flex justify-between">
                    <span class="text-gray-600">Hàng hóa:</span>
                    <span class="font-semibold">{{ $order->package_description }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-gray-600">Khối lượng:</span>
                    <span>{{ number_format($order->weight, 2) }} kg</span>
                </div>
                <div class="flex justify-between border-t pt-3">
                    <span class="text-gray-600">Phí vận chuyển:</span>
                    <span class="font-bold text-lg">{{ number_format($order->shipping_fee) }}đ</span>
                </div>
                @if($order->cod_amount > 0)
                <div class="flex justify-between">
                    <span class="text-gray-600">COD:</span>
                    <span class="font-bold text-green-600">{{ number_format($order->cod_amount) }}đ</span>
                </div>
                @endif
            </div>
        </x-card>

        <!-- Người gửi & nhận -->
        <x-card>
            <x-slot:header>
                <h3 class="text-xl font-bold">Người gửi & nhận</h3>
            </x-slot:header>
            
            <div class="space-y-4">
                <div class="pb-4 border-b">
                    <h4 class="font-semibold text-gray-700 mb-2 flex items-center gap-2">
                        <svg class="w-5 h-5 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/>
                        </svg>
                        Người gửi
                    </h4>
                    <p class="font-bold">{{ $order->sender_name }}</p>
                    <p class="text-sm text-gray-600">{{ $order->sender_phone }}</p>
                    <p class="text-sm mt-1">{{ $order->sender_address }}, {{ $order->sender_city }}</p>
                </div>

                <div>
                    <h4 class="font-semibold text-gray-700 mb-2 flex items-center gap-2">
                        <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                        </svg>
                        Người nhận
                    </h4>
                    <p class="font-bold">{{ $order->receiver_name }}</p>
                    <p class="text-sm text-gray-600">{{ $order->receiver_phone }}</p>
                    <p class="text-sm mt-1">{{ $order->receiver_address }}, {{ $order->receiver_city }}</p>
                </div>
            </div>
        </x-card>
    </div>

    <!-- Tracking thông tin -->
    @if($order->shipment)
    <x-card class="mt-6">
        <x-slot:header>
            <h3 class="text-xl font-bold">Thông tin vận chuyển</h3>
        </x-slot:header>
        
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
            <div class="info-box info-box-primary">
                <p class="text-sm text-gray-600 mb-1">Mã vận đơn</p>
                <p class="font-mono font-bold text-orange-600">{{ $order->shipment->tracking_number }}</p>
            </div>
            <div class="info-box info-box-success">
                <p class="text-sm text-gray-600 mb-1">Vị trí hiện tại</p>
                <p class="font-semibold">{{ $order->shipment->current_location }}</p>
            </div>
            <div class="info-box bg-purple-50 border-purple-200">
                <p class="text-sm text-gray-600 mb-1">Dự kiến giao</p>
                <p class="font-semibold">{{ $order->shipment->estimated_delivery?->format('d/m/Y H:i') ?? 'Chưa xác định' }}</p>
            </div>
        </div>

        @if($order->shipment->driver_name)
        <div class="info-box bg-yellow-50 border-yellow-200 mb-6">
            <h4 class="font-semibold mb-2 flex items-center gap-2">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"/>
                </svg>
                Thông tin tài xế
            </h4>
            <div class="grid grid-cols-3 gap-4 text-sm">
                <div>
                    <span class="text-gray-600">Tài xế:</span>
                    <p class="font-semibold">{{ $order->shipment->driver_name }}</p>
                </div>
                <div>
                    <span class="text-gray-600">SĐT:</span>
                    <p class="font-semibold">{{ $order->shipment->driver_phone }}</p>
                </div>
                <div>
                    <span class="text-gray-600">Biển số:</span>
                    <p class="font-semibold">{{ $order->shipment->vehicle_number }}</p>
                </div>
            </div>
        </div>
        @endif

        <!-- Lịch sử vận chuyển -->
        <h4 class="font-semibold text-lg mb-4">Lịch sử vận chuyển</h4>
        @if($order->shipmentHistories->count() > 0)
            <div class="space-y-4">
                @foreach($order->shipmentHistories as $index => $history)
                <div class="flex">
                    <div class="flex-shrink-0">
                        <div class="{{ $index === 0 ? 'timeline-marker-active' : 'timeline-marker-inactive' }}"></div>
                    </div>
                    <div class="ml-6 flex-grow border-l-2 {{ $index === 0 ? 'border-orange-200' : 'border-gray-200' }} pl-4 pb-4">
                        <div class="bg-white border {{ $index === 0 ? 'border-orange-300 shadow-md' : 'border-gray-200' }} rounded-lg p-4">
                            <p class="font-bold {{ $index === 0 ? 'text-orange-600' : '' }}">
                                {{ \App\Models\Order::STATUS_LABELS[$history->status] ?? $history->status }}
                            </p>
                            @if($history->location)
                                <p class="text-sm text-gray-600 mt-1">
                                    <svg class="w-4 h-4 inline-block" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                    </svg>
                                    {{ $history->location }}
                                </p>
                            @endif
                            @if($history->notes)
                                <p class="text-sm text-gray-500 italic mt-2">{{ $history->notes }}</p>
                            @endif
                            <p class="text-xs text-gray-400 mt-2">
                                <svg class="w-4 h-4 inline-block" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                                {{ $history->happened_at->format('d/m/Y H:i:s') }}
                            </p>
                            @if($history->updatedByUser)
                                <p class="text-xs text-gray-400">
                                    <svg class="w-4 h-4 inline-block" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                    </svg>
                                    Cập nhật bởi: {{ $history->updatedByUser->name }}
                                </p>
                            @endif
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        @else
            <p class="text-gray-500 text-center py-8">Chưa có lịch sử vận chuyển</p>
        @endif
    </x-card>
    @endif
</x-layout>
