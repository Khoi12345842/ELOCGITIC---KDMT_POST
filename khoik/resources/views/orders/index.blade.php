<x-layout title="Danh sách đơn hàng - FastShip Logistics">
    <div class="flex justify-between items-center mb-6">
        <x-page-header 
            title="Danh sách đơn hàng" 
            subtitle="Tổng số: {{ $orders->total() }} đơn hàng"
        />

        @auth
            @if(auth()->user()->isBusiness())
                <a href="{{ route('orders.create.bulk') }}" class="btn btn-primary">
                    Lên đơn theo lô
                </a>
            @else
                <a href="{{ route('orders.create.individual') }}" class="btn btn-primary">
                    Tạo đơn mới
                </a>
            @endif
        @endauth
    </div>

    <div class="bg-white border border-gray-200 rounded-xl shadow-sm p-4 mb-6">
        <form method="GET" action="{{ route('orders.index') }}" class="grid gap-4 md:grid-cols-5">
            <div class="md:col-span-2">
                <label for="search" class="block text-xs font-semibold text-gray-500 uppercase tracking-wide mb-1">Tìm kiếm</label>
                <input
                    type="text"
                    id="search"
                    name="search"
                    value="{{ $filters['search'] }}"
                    placeholder="Mã đơn, tên người nhận, điện thoại, mã vận đơn..."
                    class="w-full rounded-lg border-gray-300 focus:border-orange-500 focus:ring-orange-500"
                />
            </div>

            <div>
                <label for="status" class="block text-xs font-semibold text-gray-500 uppercase tracking-wide mb-1">Trạng thái</label>
                <select
                    id="status"
                    name="status"
                    class="w-full rounded-lg border-gray-300 focus:border-orange-500 focus:ring-orange-500"
                >
                    <option value="">Tất cả</option>
                    @foreach ($statusOptions as $value => $label)
                        <option value="{{ $value }}" @selected($filters['status'] === $value)>{{ $label }}</option>
                    @endforeach
                </select>
            </div>

            <div>
                <label for="type" class="block text-xs font-semibold text-gray-500 uppercase tracking-wide mb-1">Loại đơn</label>
                <select
                    id="type"
                    name="type"
                    class="w-full rounded-lg border-gray-300 focus:border-orange-500 focus:ring-orange-500"
                >
                    <option value="">Tất cả</option>
                    @foreach ($typeOptions as $value => $label)
                        <option value="{{ $value }}" @selected($filters['type'] === $value)>{{ $label }}</option>
                    @endforeach
                </select>
            </div>

            <div>
                <label for="date_from" class="block text-xs font-semibold text-gray-500 uppercase tracking-wide mb-1">Từ ngày</label>
                <input
                    type="date"
                    id="date_from"
                    name="date_from"
                    value="{{ $filters['date_from'] }}"
                    class="w-full rounded-lg border-gray-300 focus:border-orange-500 focus:ring-orange-500"
                />
            </div>

            <div>
                <label for="date_to" class="block text-xs font-semibold text-gray-500 uppercase tracking-wide mb-1">Đến ngày</label>
                <input
                    type="date"
                    id="date_to"
                    name="date_to"
                    value="{{ $filters['date_to'] }}"
                    class="w-full rounded-lg border-gray-300 focus:border-orange-500 focus:ring-orange-500"
                />
            </div>

            <div class="md:col-span-5 flex flex-wrap items-center gap-2 justify-end">
                <button type="submit" class="inline-flex items-center gap-2 bg-orange-600 text-white px-4 py-2 rounded-lg font-semibold hover:bg-orange-700 transition">
                    <span>Áp dụng</span>
                </button>

                @if ($hasFilters)
                    <a href="{{ route('orders.index') }}" class="inline-flex items-center gap-2 px-4 py-2 rounded-lg border border-gray-300 text-gray-600 hover:bg-gray-50 transition">
                        Xóa lọc
                    </a>
                @endif
            </div>
        </form>
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
                @forelse($orders as $order)
                <tr class="table-row">
                    <td class="table-td">
                        <span class="font-mono text-sm font-semibold text-orange-600">{{ $order->order_number }}</span>
                    </td>
                    <td class="table-td">
                        @if($order->order_type === 'fake')
                            <span class="badge bg-gray-100 text-gray-600">Demo</span>
                        @elseif($order->order_type === 'manual')
                            <span class="badge bg-blue-100 text-blue-800">Thủ công</span>
                        @elseif($order->order_type === 'shop_sync')
                            <span class="badge bg-purple-100 text-purple-800">{{ $order->shop_platform }}</span>
                        @elseif($order->order_type === 'bulk')
                            <span class="badge bg-green-100 text-green-800">Bulk</span>
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
                @empty
                <tr>
                    <td colspan="8" class="table-td text-center text-gray-500 py-8">
                        Không tìm thấy đơn hàng phù hợp với bộ lọc hiện tại.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-6">
        {{ $orders->links() }}
    </div>
</x-layout>
