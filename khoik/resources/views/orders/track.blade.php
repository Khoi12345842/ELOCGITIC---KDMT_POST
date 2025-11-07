<x-layout title="Tra cứu đơn hàng - FastShip">
    <div class="max-w-7xl mx-auto">
        <!-- Hero Section -->
        <div class="bg-gradient-to-r from-orange-600 to-orange-500 text-white rounded-2xl p-8 mb-8 shadow-xl">
            <div class="text-center">
                <h1 class="text-4xl font-bold mb-3">🔍 Tra cứu đơn hàng</h1>
                <p class="text-orange-100 text-lg">Nhập mã vận đơn để theo dõi hành trình giao hàng của bạn</p>
            </div>

            <!-- Form tìm kiếm -->
            <form method="GET" action="{{ route('orders.track') }}" class="mt-8">
                <div class="max-w-3xl mx-auto">
                    <div class="flex gap-3">
                        <div class="flex-1 relative">
                            <input 
                                type="text" 
                                name="tracking_number" 
                                placeholder="Nhập mã vận đơn (VD: SHIP20241108...)" 
                                class="w-full px-6 py-4 text-gray-900 rounded-xl focus:outline-none focus:ring-4 focus:ring-orange-300 text-lg shadow-lg"
                                value="{{ request('tracking_number') }}"
                                required
                                autofocus
                            >
                            <svg class="absolute right-4 top-1/2 transform -translate-y-1/2 w-6 h-6 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                            </svg>
                        </div>
                        <button type="submit" class="px-8 py-4 bg-blue-900 text-white rounded-xl hover:bg-blue-950 font-bold text-lg shadow-lg transition-all hover:shadow-xl">
                            Tra cứu
                        </button>
                    </div>
                </div>
            </form>
        </div>

        @if(isset($shipment))
        <!-- Kết quả tracking -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Left Column: Timeline -->
            <div class="lg:col-span-2 space-y-6">
                <!-- Trạng thái hiện tại -->
                <x-card class="border-2 border-orange-500">
                    <div class="flex items-start gap-6">
                        <div class="w-20 h-20 bg-orange-100 rounded-full flex items-center justify-center flex-shrink-0">
                            <span class="text-4xl">
                                @if($shipment->order->status === 'delivered')
                                    ✅
                                @elseif(in_array($shipment->order->status, ['in_transit', 'out_delivery']))
                                    🚚
                                @elseif($shipment->order->status === 'picked_up')
                                    📦
                                @else
                                    ⏳
                                @endif
                            </span>
                        </div>
                        <div class="flex-1">
                            <h2 class="text-2xl font-bold text-gray-900 mb-2">{{ $shipment->current_status }}</h2>
                            <p class="text-gray-600 mb-1">📍 {{ $shipment->current_location }}</p>
                            <p class="text-sm text-gray-500">Cập nhật: {{ $shipment->updated_at->format('H:i - d/m/Y') }}</p>
                        </div>
                    </div>
                </x-card>

                <!-- Progress Bar -->
                <x-card>
                    <h3 class="font-bold text-lg mb-4">📊 Tiến trình vận chuyển</h3>
                    <div class="relative">
                        @php
                            $statusFlow = ['pending', 'confirmed', 'picked_up', 'in_transit', 'out_delivery', 'delivered'];
                            $currentIndex = array_search($shipment->order->status, $statusFlow);
                            $progress = $currentIndex !== false ? (($currentIndex + 1) / count($statusFlow)) * 100 : 0;
                        @endphp
                        
                        <!-- Progress bar -->
                        <div class="h-2 bg-gray-200 rounded-full mb-8">
                            <div class="h-full bg-gradient-to-r from-orange-500 to-green-500 rounded-full transition-all duration-500" style="width: {{ $progress }}%"></div>
                        </div>

                        <!-- Steps -->
                        <div class="grid grid-cols-6 gap-2">
                            @foreach(['pending' => '⏳', 'confirmed' => '✓', 'picked_up' => '📦', 'in_transit' => '🚚', 'out_delivery' => '🏃', 'delivered' => '✅'] as $status => $icon)
                            @php
                                $stepIndex = array_search($status, $statusFlow);
                                $isActive = $stepIndex <= $currentIndex;
                            @endphp
                            <div class="text-center">
                                <div class="w-10 h-10 mx-auto rounded-full flex items-center justify-center text-lg mb-2 {{ $isActive ? 'bg-orange-500 text-white' : 'bg-gray-200 text-gray-500' }}">
                                    {{ $icon }}
                                </div>
                                <p class="text-xs {{ $isActive ? 'font-semibold text-gray-900' : 'text-gray-500' }}">
                                    {{ ['pending' => 'Chờ', 'confirmed' => 'Xác nhận', 'picked_up' => 'Lấy hàng', 'in_transit' => 'Vận chuyển', 'out_delivery' => 'Đang giao', 'delivered' => 'Hoàn tất'][$status] }}
                                </p>
                            </div>
                            @endforeach
                        </div>
                    </div>
                </x-card>

                <!-- Timeline lịch sử -->
                <x-card>
                    <x-slot:header>
                        <h3 class="text-lg font-bold">📅 Lịch sử di chuyển</h3>
                    </x-slot:header>

                    <div class="space-y-4">
                        @foreach($shipment->histories->sortByDesc('happened_at') as $index => $history)
                        <div class="flex gap-4 {{ $index === 0 ? 'bg-orange-50 -mx-6 px-6 py-4 rounded-lg' : '' }}">
                            <div class="flex-shrink-0 pt-1">
                                <div class="w-3 h-3 {{ $index === 0 ? 'bg-orange-600 ring-4 ring-orange-200' : 'bg-gray-300' }} rounded-full"></div>
                                @if($index < count($shipment->histories) - 1)
                                <div class="w-0.5 h-12 bg-gray-200 ml-1 mt-1"></div>
                                @endif
                            </div>
                            <div class="flex-1">
                                <div class="flex items-start justify-between gap-4">
                                    <div>
                                        <p class="font-bold {{ $index === 0 ? 'text-orange-600' : 'text-gray-900' }}">
                                            {{ $history->status }}
                                        </p>
                                        <p class="text-sm text-gray-600 mt-1">📍 {{ $history->location }}</p>
                                        @if($history->description)
                                        <p class="text-sm text-gray-500 italic mt-1">{{ $history->description }}</p>
                                        @endif
                                        @if($history->updated_by)
                                        <p class="text-xs text-gray-400 mt-1">👤 {{ $history->updated_by }}</p>
                                        @endif
                                    </div>
                                    <div class="text-right flex-shrink-0">
                                        <p class="text-sm font-semibold text-gray-900">{{ $history->happened_at->format('H:i') }}</p>
                                        <p class="text-xs text-gray-500">{{ $history->happened_at->format('d/m/Y') }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </x-card>
            </div>

            <!-- Right Column: Info Cards -->
            <div class="space-y-6">
                <!-- Thông tin vận đơn -->
                <x-card class="bg-gradient-to-br from-blue-50 to-blue-100 border-blue-200">
                    <h3 class="font-bold text-lg mb-4">📦 Thông tin vận đơn</h3>
                    <div class="space-y-3">
                        <div>
                            <p class="text-sm text-gray-600">Mã vận đơn</p>
                            <p class="font-mono font-bold text-blue-900">{{ $shipment->tracking_number }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-600">Mã đơn hàng</p>
                            <p class="font-mono font-bold text-blue-900">{{ $shipment->order->order_number }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-600">Loại dịch vụ</p>
                            <p class="font-semibold">{{ $shipment->service_type ?? 'Tiêu chuẩn' }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-600">Phí vận chuyển</p>
                            <p class="font-bold text-orange-600">{{ number_format($shipment->order->shipping_fee) }}đ</p>
                        </div>
                    </div>
                </x-card>

                <!-- Thông tin giao nhận -->
                <x-card class="bg-gradient-to-br from-green-50 to-green-100 border-green-200">
                    <h3 class="font-bold text-lg mb-4">📍 Thông tin giao nhận</h3>
                    
                    <div class="space-y-4">
                        <div class="bg-white rounded-lg p-3">
                            <p class="text-xs text-gray-500 mb-1">Người gửi</p>
                            <p class="font-bold text-gray-900">{{ $shipment->order->sender_name }}</p>
                            <p class="text-sm text-gray-600">📞 {{ $shipment->order->sender_phone }}</p>
                            <p class="text-sm text-gray-600">📍 {{ $shipment->order->sender_city }}</p>
                        </div>

                        <div class="flex justify-center">
                            <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 14l-7 7m0 0l-7-7m7 7V3"/>
                            </svg>
                        </div>

                        <div class="bg-white rounded-lg p-3">
                            <p class="text-xs text-gray-500 mb-1">Người nhận</p>
                            <p class="font-bold text-gray-900">{{ $shipment->order->receiver_name }}</p>
                            <p class="text-sm text-gray-600">📞 {{ $shipment->order->receiver_phone }}</p>
                            <p class="text-sm text-gray-600">📍 {{ $shipment->order->receiver_city }}</p>
                        </div>
                    </div>
                </x-card>

                @if($shipment->driver_name)
                <!-- Thông tin tài xế -->
                <x-card class="bg-gradient-to-br from-yellow-50 to-yellow-100 border-yellow-200">
                    <h3 class="font-bold text-lg mb-4">🚗 Thông tin tài xế</h3>
                    <div class="space-y-3">
                        <div>
                            <p class="text-sm text-gray-600">Tài xế</p>
                            <p class="font-bold">{{ $shipment->driver_name }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-600">Số điện thoại</p>
                            <p class="font-bold">{{ $shipment->driver_phone }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-600">Biển số xe</p>
                            <p class="font-mono font-bold">{{ $shipment->vehicle_number }}</p>
                        </div>
                    </div>
                    <a href="tel:{{ $shipment->driver_phone }}" class="btn btn-sm btn-primary w-full mt-4">
                        📞 Gọi tài xế
                    </a>
                </x-card>
                @endif

                <!-- Actions -->
                <x-card>
                    <div class="space-y-2">
                        <a href="{{ route('orders.show', $shipment->order->id) }}" class="btn btn-outline w-full">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                            </svg>
                            Xem chi tiết đơn hàng
                        </a>
                        <button onclick="window.print()" class="btn btn-outline w-full">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/>
                            </svg>
                            In thông tin
                        </button>
                    </div>
                </x-card>
            </div>
        </div>
        @endif

        @if(!isset($shipment) && request()->has('tracking_number'))
        <!-- Không tìm thấy -->
        <x-card class="text-center py-12">
            <div class="text-6xl mb-4">😞</div>
            <h3 class="text-2xl font-bold text-gray-900 mb-2">Không tìm thấy vận đơn</h3>
            <p class="text-gray-600 mb-6">Mã vận đơn "{{ request('tracking_number') }}" không tồn tại trong hệ thống</p>
            <a href="{{ route('orders.track') }}" class="btn btn-primary">
                Thử lại
            </a>
        </x-card>
        @endif

        <!-- Gợi ý mã vận đơn -->
        @if(!isset($shipment))
        <div class="mt-8">
            <x-card>
                <x-slot:header>
                    <h3 class="text-lg font-bold">💡 Gợi ý: Thử tra cứu các đơn hàng mẫu</h3>
                </x-slot:header>

                @php
                    $sampleShipments = \App\Models\Shipment::with('order')
                        ->orderBy('created_at', 'desc')
                        ->take(8)
                        ->get();
                @endphp

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    @foreach($sampleShipments as $sample)
                    <a href="{{ route('orders.track') }}?tracking_number={{ $sample->tracking_number }}" 
                       class="block p-4 border-2 border-gray-200 rounded-lg hover:border-orange-500 hover:bg-orange-50 transition-all group">
                        <div class="flex items-start justify-between gap-4">
                            <div class="flex-1">
                                <p class="font-mono font-bold text-sm text-orange-600 group-hover:text-orange-700">
                                    {{ $sample->tracking_number }}
                                </p>
                                <p class="text-sm text-gray-600 mt-1">
                                    {{ $sample->order->receiver_name }} - {{ $sample->order->receiver_city }}
                                </p>
                                <p class="text-xs text-gray-500 mt-1">
                                    @if($sample->order->status === 'delivered')
                                        ✅ Đã giao
                                    @elseif(in_array($sample->order->status, ['in_transit', 'out_delivery']))
                                        🚚 Đang giao
                                    @else
                                        ⏳ Đang xử lý
                                    @endif
                                </p>
                            </div>
                            <svg class="w-5 h-5 text-gray-400 group-hover:text-orange-500 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                            </svg>
                        </div>
                    </a>
                    @endforeach
                </div>
            </x-card>
        </div>
        @endif
    </div>
</x-layout>
