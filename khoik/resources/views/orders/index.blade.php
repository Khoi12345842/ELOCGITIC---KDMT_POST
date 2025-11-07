<x-layout title="Danh sách đơn hàng - FastShip Logistics">
    <div class="flex justify-between items-center mb-6">
        <x-page-header 
            title="📦 Danh sách đơn hàng" 
            subtitle="Tổng số: {{ $orders->total() }} đơn hàng"
        />

        @auth
            @if(auth()->user()->isBusiness())
                <a href="{{ route('orders.create.bulk') }}" class="btn btn-primary">
                    ➕ Lên đơn theo lô
                </a>
            @else
                <a href="{{ route('orders.create.individual') }}" class="btn btn-primary">
                    ➕ Tạo đơn mới
                </a>
            @endif
        @endauth
    </div>

    <div class="table-wrapper">
        <table class="table">
            <thead>
                <tr>
                    <th class="table-th">Mã đơn</th>
                    <th class="table-th">Loại đơn</th>
                    <th class="table-th">Người nhận</th>
                    <th class="table-th">Điểm đến</th>
                    <th class="table-th">Trạng thái</th>
                    <th class="table-th">Mã vận đơn</th>
                    <th class="table-th">Ngày tạo</th>
                    <th class="table-th">Hành động</th>
                </tr>
            </thead>
            <tbody>
                @foreach($orders as $order)
                <tr class="table-row">
                    <td class="table-td">
                        <span class="font-mono text-sm font-semibold text-orange-600">{{ $order->order_number }}</span>
                    </td>
                    <td class="table-td">
                        @if($order->order_type === 'fake')
                            <span class="badge bg-gray-100 text-gray-600">🎭 Demo</span>
                        @elseif($order->order_type === 'manual')
                            <span class="badge bg-blue-100 text-blue-800">✍️ Thủ công</span>
                        @elseif($order->order_type === 'shop_sync')
                            <span class="badge bg-purple-100 text-purple-800">🏪 {{ $order->shop_platform }}</span>
                        @elseif($order->order_type === 'bulk')
                            <span class="badge bg-green-100 text-green-800">📊 Bulk</span>
                        @endif
                    </td>
                    <td class="table-td">
                        <div class="font-medium text-gray-900">{{ $order->receiver_name }}</div>
                        <div class="text-xs text-gray-500">{{ $order->receiver_phone }}</div>
                    </td>
                    <td class="table-td">
                        <span class="text-sm">{{ $order->receiver_city }}</span>
                    </td>
                    <td class="table-td">
                        <x-order-status-badge :status="$order->status" />
                    </td>
                    <td class="table-td">
                        @if($order->shipment)
                            <span class="font-mono text-xs text-blue-600">{{ $order->shipment->tracking_number }}</span>
                        @else
                            <span class="text-gray-400 text-xs">Chưa có</span>
                        @endif
                    </td>
                    <td class="table-td whitespace-nowrap text-sm text-gray-500">
                        {{ $order->created_at->format('d/m/Y H:i') }}
                    </td>
                    <td class="table-td">
                        <x-button 
                            :href="route('orders.show', $order->id)" 
                            variant="ghost" 
                            size="sm"
                        >
                            Xem chi tiết →
                        </x-button>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <div class="mt-6">
        {{ $orders->links() }}
    </div>
</x-layout>
