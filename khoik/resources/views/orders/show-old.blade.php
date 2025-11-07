@extends('layouts.app')

@section('title', 'Chi tiết đơn hàng')

@section('content')
<div class="mb-6">
    <a href="{{ route('orders.index') }}" class="text-blue-600 hover:underline">&larr; Quay lại danh sách</a>
</div>

<div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
    <!-- Thông tin đơn hàng -->
    <div class="bg-white rounded-lg shadow p-6">
        <h3 class="text-xl font-bold mb-4">📋 Thông tin đơn hàng</h3>
        
        <div class="space-y-3">
            <div>
                <span class="text-gray-600">Mã đơn hàng:</span>
                <span class="font-mono font-bold">{{ $order->order_number }}</span>
            </div>
            <div>
                <span class="text-gray-600">Trạng thái:</span>
                <span class="font-semibold text-blue-600">{{ $order->status_label }}</span>
            </div>
            <div>
                <span class="text-gray-600">Hàng hóa:</span>
                <span>{{ $order->package_description }}</span>
            </div>
            <div>
                <span class="text-gray-600">Khối lượng:</span>
                <span>{{ number_format($order->weight, 2) }} kg</span>
            </div>
            <div>
                <span class="text-gray-600">Phí vận chuyển:</span>
                <span class="font-bold">{{ number_format($order->shipping_fee) }} VNĐ</span>
            </div>
            @if($order->cod_amount > 0)
            <div>
                <span class="text-gray-600">COD:</span>
                <span class="font-bold text-green-600">{{ number_format($order->cod_amount) }} VNĐ</span>
            </div>
            @endif
        </div>
    </div>

    <!-- Người gửi & nhận -->
    <div class="bg-white rounded-lg shadow p-6">
        <h3 class="text-xl font-bold mb-4">👥 Người gửi & nhận</h3>
        
        <div class="mb-4 pb-4 border-b">
            <h4 class="font-semibold text-gray-700 mb-2">📤 Người gửi</h4>
            <p class="font-bold">{{ $order->sender_name }}</p>
            <p class="text-sm text-gray-600">{{ $order->sender_phone }}</p>
            <p class="text-sm">{{ $order->sender_address }}, {{ $order->sender_city }}</p>
        </div>

        <div>
            <h4 class="font-semibold text-gray-700 mb-2">📥 Người nhận</h4>
            <p class="font-bold">{{ $order->receiver_name }}</p>
            <p class="text-sm text-gray-600">{{ $order->receiver_phone }}</p>
            <p class="text-sm">{{ $order->receiver_address }}, {{ $order->receiver_city }}</p>
        </div>
    </div>
</div>

<!-- Tracking thông tin -->
@if($order->shipment)
<div class="mt-6 bg-white rounded-lg shadow p-6">
    <h3 class="text-xl font-bold mb-4">📍 Thông tin vận chuyển</h3>
    
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
        <div class="bg-blue-50 p-4 rounded">
            <p class="text-sm text-gray-600">Mã vận đơn</p>
            <p class="font-mono font-bold text-blue-600">{{ $order->shipment->tracking_number }}</p>
        </div>
        <div class="bg-green-50 p-4 rounded">
            <p class="text-sm text-gray-600">Vị trí hiện tại</p>
            <p class="font-semibold">{{ $order->shipment->current_location }}</p>
        </div>
        <div class="bg-purple-50 p-4 rounded">
            <p class="text-sm text-gray-600">Dự kiến giao</p>
            <p class="font-semibold">{{ $order->shipment->estimated_delivery?->format('d/m/Y H:i') ?? 'Chưa xác định' }}</p>
        </div>
    </div>

    @if($order->shipment->driver_name)
    <div class="bg-yellow-50 p-4 rounded mb-6">
        <h4 class="font-semibold mb-2">🚗 Thông tin tài xế</h4>
        <p><strong>Tài xế:</strong> {{ $order->shipment->driver_name }}</p>
        <p><strong>SĐT:</strong> {{ $order->shipment->driver_phone }}</p>
        <p><strong>Biển số:</strong> {{ $order->shipment->vehicle_number }}</p>
    </div>
    @endif

    <!-- Lịch sử vận chuyển -->
    <h4 class="font-semibold text-lg mb-4">📅 Lịch sử vận chuyển</h4>
    <div class="space-y-4">
        @foreach($order->shipment->histories as $history)
        <div class="flex">
            <div class="flex-shrink-0">
                <div class="w-3 h-3 bg-blue-600 rounded-full mt-1.5"></div>
            </div>
            <div class="ml-4 flex-grow border-l-2 border-gray-200 pl-4 pb-4">
                <p class="font-semibold">{{ $history->status }}</p>
                <p class="text-sm text-gray-600">{{ $history->location }}</p>
                <p class="text-sm text-gray-500 italic">{{ $history->description }}</p>
                <p class="text-xs text-gray-400 mt-1">{{ $history->happened_at->format('d/m/Y H:i:s') }}</p>
            </div>
        </div>
        @endforeach
    </div>
</div>
@endif

@endsection
