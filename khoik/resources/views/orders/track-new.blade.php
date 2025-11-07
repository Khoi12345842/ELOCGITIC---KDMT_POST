<x-layout title="Tra cứu vận đơn">
    <div class="max-w-4xl mx-auto">
        <x-card>
            <x-slot:header>
                <h2 class="text-3xl font-bold text-center">🔍 Tra cứu vận đơn</h2>
            </x-slot:header>
            
            <!-- Form tìm kiếm -->
            <form method="GET" action="{{ route('orders.track') }}" class="mb-8">
                <div class="flex gap-4">
                    <input 
                        type="text" 
                        name="tracking_number" 
                        placeholder="Nhập mã vận đơn (VD: SHIP20231107...)" 
                        class="form-input flex-1"
                        value="{{ request('tracking_number') }}"
                        required
                    >
                    <button type="submit" class="btn btn-primary">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                        </svg>
                        Tra cứu
                    </button>
                </div>
            </form>

            @if(isset($shipment))
            <!-- Kết quả tracking -->
            <div class="border-t pt-6">
                <div class="info-box info-box-primary mb-6">
                    <h3 class="text-xl font-bold mb-4">📦 Thông tin vận đơn</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <p class="text-sm text-gray-600">Mã vận đơn</p>
                            <p class="font-mono font-bold text-lg text-orange-600">{{ $shipment->tracking_number }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-600">Mã đơn hàng</p>
                            <p class="font-mono font-bold text-lg">{{ $shipment->order->order_number }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-600">Trạng thái hiện tại</p>
                            <p class="font-semibold text-green-600">{{ $shipment->current_status }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-600">Vị trí hiện tại</p>
                            <p class="font-semibold">{{ $shipment->current_location }}</p>
                        </div>
                    </div>
                </div>

                <div class="info-box bg-gray-50 border-gray-200 mb-6">
                    <h4 class="font-semibold mb-3">📍 Thông tin giao nhận</h4>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <p class="text-sm font-semibold text-gray-700">Từ:</p>
                            <p class="font-bold">{{ $shipment->order->sender_name }}</p>
                            <p class="text-sm">{{ $shipment->order->sender_city }}</p>
                        </div>
                        <div>
                            <p class="text-sm font-semibold text-gray-700">Đến:</p>
                            <p class="font-bold">{{ $shipment->order->receiver_name }}</p>
                            <p class="text-sm">{{ $shipment->order->receiver_city }}</p>
                        </div>
                    </div>
                </div>

                @if($shipment->driver_name)
                <div class="info-box bg-yellow-50 border-yellow-200 mb-6">
                    <h4 class="font-semibold mb-3">🚗 Thông tin tài xế</h4>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <div>
                            <p class="text-sm text-gray-600">Tài xế</p>
                            <p class="font-semibold">{{ $shipment->driver_name }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-600">Số điện thoại</p>
                            <p class="font-semibold">{{ $shipment->driver_phone }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-600">Biển số xe</p>
                            <p class="font-semibold">{{ $shipment->vehicle_number }}</p>
                        </div>
                    </div>
                </div>
                @endif

                <!-- Timeline lịch sử -->
                <h4 class="font-semibold text-lg mb-4">📅 Lịch sử di chuyển</h4>
                <div class="relative">
                    @foreach($shipment->histories as $index => $history)
                    <div class="flex mb-6 {{ $index === 0 ? 'text-blue-600' : '' }}">
                        <div class="flex-shrink-0">
                            <div class="{{ $index === 0 ? 'timeline-marker-active' : 'timeline-marker-inactive' }}"></div>
                        </div>
                        <div class="ml-6 flex-grow">
                            <div class="bg-white border {{ $index === 0 ? 'border-orange-300 shadow-md' : 'border-gray-200' }} rounded-lg p-4">
                                <p class="font-bold {{ $index === 0 ? 'text-orange-600' : '' }}">{{ $history->status }}</p>
                                <p class="text-sm text-gray-600 mt-1">
                                    <svg class="w-4 h-4 inline-block" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                    </svg>
                                    {{ $history->location }}
                                </p>
                                <p class="text-sm text-gray-500 italic mt-2">{{ $history->description }}</p>
                                <p class="text-xs text-gray-400 mt-2">
                                    🕐 {{ $history->happened_at->format('d/m/Y H:i:s') }}
                                </p>
                                @if($history->updated_by)
                                <p class="text-xs text-gray-400">👤 {{ $history->updated_by }}</p>
                                @endif
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>

                <div class="mt-6 text-center">
                    <x-button :href="route('orders.show', $shipment->order->id)" variant="outline">
                        Xem chi tiết đơn hàng →
                    </x-button>
                </div>
            </div>
            @endif

            @if(!isset($shipment) && request()->has('tracking_number'))
            <div class="text-center text-gray-500 py-8">
                <svg class="w-16 h-16 mx-auto text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                <p class="text-xl font-semibold">Không tìm thấy vận đơn</p>
                <p class="mt-2">Vui lòng kiểm tra lại mã vận đơn</p>
            </div>
            @endif

            <x-slot:footer>
                <!-- Gợi ý mã vận đơn mẫu -->
                @if(!isset($shipment))
                <div class="info-box bg-blue-50 border-blue-200">
                    <h4 class="font-semibold mb-3 flex items-center gap-2">
                        <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        💡 Mã vận đơn mẫu để thử
                    </h4>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-2">
                        @php
                            $sampleShipments = \App\Models\Shipment::take(6)->get();
                        @endphp
                        @foreach($sampleShipments as $sample)
                        <a href="{{ route('orders.track') }}?tracking_number={{ $sample->tracking_number }}" 
                           class="flex items-center justify-between p-3 bg-white rounded border border-blue-200 hover:border-orange-500 hover:shadow-md transition-all group">
                            <span class="font-mono text-sm text-gray-700 group-hover:text-orange-600">{{ $sample->tracking_number }}</span>
                            <svg class="w-4 h-4 text-gray-400 group-hover:text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                            </svg>
                        </a>
                        @endforeach
                    </div>
                    <p class="text-xs text-gray-500 mt-3 text-center">Click vào mã để tra cứu ngay</p>
                </div>
                @endif
            </x-slot:footer>
        </x-card>
    </div>
</x-layout>
