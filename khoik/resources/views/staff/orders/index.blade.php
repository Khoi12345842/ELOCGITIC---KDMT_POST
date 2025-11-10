<x-staff-layout title="Đơn được phân công">
    <div class="flex justify-between items-center mb-6">
        <x-page-header
            title="Đơn hàng được phân công"
            subtitle="Tổng số: {{ $orders->total() }} đơn"
        />
    </div>

    <div class="bg-white border border-gray-200 rounded-lg shadow-sm p-3 mb-4">
        <form method="GET" action="{{ route('staff.orders.index') }}" class="space-y-3">
            <!-- Row 1: Search -->
            <div class="grid gap-2 md:grid-cols-12">
                <div class="md:col-span-12">
                    <label for="search" class="block text-xs font-medium text-gray-600 mb-1">🔍 Tìm kiếm</label>
                    <input
                        type="text"
                        id="search"
                        name="search"
                        value="{{ $filters['search'] }}"
                        placeholder="Nhập mã đơn, tên người nhận, SĐT, mã vận đơn..."
                        class="w-full text-sm rounded-md border-gray-300 focus:border-orange-500 focus:ring-orange-500 py-1.5"
                    />
                </div>
            </div>

            <!-- Row 2: Filters -->
            <div class="grid gap-2 md:grid-cols-12 items-end">
                <div class="md:col-span-3">
                    <label for="status" class="block text-xs font-medium text-gray-600 mb-1">📦 Trạng thái</label>
                    <select
                        id="status"
                        name="status"
                        class="w-full text-sm rounded-md border-gray-300 focus:border-orange-500 focus:ring-orange-500 py-1.5"
                    >
                        <option value="">Tất cả</option>
                        @foreach ($statusOptions as $value => $label)
                            <option value="{{ $value }}" @selected($filters['status'] === $value)>{{ $label }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="md:col-span-2">
                    <label for="route" class="block text-xs font-medium text-gray-600 mb-1">🚚 Tuyến</label>
                    <input
                        type="text"
                        id="route"
                        name="route"
                        value="{{ $filters['route'] }}"
                        placeholder="VD: HN-HCM"
                        class="w-full text-sm rounded-md border-gray-300 focus:border-orange-500 focus:ring-orange-500 py-1.5"
                    />
                </div>

                <div class="md:col-span-2">
                    <label for="date_from" class="block text-xs font-medium text-gray-600 mb-1">📅 Từ ngày</label>
                    <input
                        type="date"
                        id="date_from"
                        name="date_from"
                        value="{{ $filters['date_from'] }}"
                        class="w-full text-sm rounded-md border-gray-300 focus:border-orange-500 focus:ring-orange-500 py-1.5"
                    />
                </div>

                <div class="md:col-span-2">
                    <label for="date_to" class="block text-xs font-medium text-gray-600 mb-1">📅 Đến ngày</label>
                    <input
                        type="date"
                        id="date_to"
                        name="date_to"
                        value="{{ $filters['date_to'] }}"
                        class="w-full text-sm rounded-md border-gray-300 focus:border-orange-500 focus:ring-orange-500 py-1.5"
                    />
                </div>

                <div class="md:col-span-3 flex items-center gap-2">
                    <button type="submit" class="flex-1 inline-flex items-center justify-center gap-1.5 bg-orange-600 text-white px-3 py-1.5 rounded-md text-sm font-medium hover:bg-orange-700 transition">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"/>
                        </svg>
                        Lọc
                    </button>

                    @if ($hasFilters)
                        <a href="{{ route('staff.orders.index') }}" class="inline-flex items-center justify-center gap-1.5 px-3 py-1.5 rounded-md border border-gray-300 text-gray-600 text-sm hover:bg-gray-50 transition">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                            </svg>
                            Xóa
                        </a>
                    @endif
                </div>
            </div>
        </form>
    </div>

    <div class="table-wrapper">
        <table class="table">
            <thead>
                <tr>
                    <th class="table-th">Mã đơn</th>
                    <th class="table-th">Khách hàng</th>
                    <th class="table-th">Người nhận</th>
                    <th class="table-th">Tuyến</th>
                    <th class="table-th">Trạng thái</th>
                    <th class="table-th">Mã vận đơn</th>
                    <th class="table-th">Ngày giao dự kiến</th>
                    <th class="table-th">Thao tác</th>
                </tr>
            </thead>
            <tbody>
                @forelse($orders as $order)
                    <tr class="table-row">
                        <td class="table-td font-mono text-sm font-semibold text-orange-600">
                            <a href="{{ route('orders.show', $order->id) }}" class="hover:underline">
                                {{ $order->order_number }}
                            </a>
                        </td>
                        <td class="table-td text-sm">
                            <div class="font-medium text-gray-900">{{ $order->user?->name ?? 'Không xác định' }}</div>
                            <div class="text-xs text-gray-500">{{ $order->user?->phone }}</div>
                        </td>
                        <td class="table-td text-sm">
                            <div class="font-medium text-gray-900">{{ $order->receiver_name }}</div>
                            <div class="text-xs text-gray-500">{{ $order->receiver_phone }}</div>
                        </td>
                        <td class="table-td text-sm">
                            <div>{{ $order->route_code ?? 'Chưa gán' }}</div>
                            <div class="text-xs text-gray-500">{{ $order->receiver_city }}</div>
                        </td>
                        <td class="table-td">
                            <x-order-status-badge :status="$order->status" />
                        </td>
                        <td class="table-td">
                            @if($order->shipment)
                                <span class="font-mono text-xs text-blue-600">{{ $order->shipment->tracking_number }}</span>
                            @else
                                <span class="text-gray-400 text-xs">Chưa cấp</span>
                            @endif
                        </td>
                        <td class="table-td whitespace-nowrap text-sm text-gray-500">
                            {{ optional($order->scheduled_date)->format('d/m/Y') ?? 'Chưa đặt' }}
                        </td>
                        <td class="table-td">
                            <a href="{{ route('staff.orders.status.edit', $order) }}" 
                               class="inline-flex items-center gap-1 px-3 py-1.5 bg-orange-600 text-white text-xs font-semibold rounded-lg hover:bg-orange-700 transition">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                </svg>
                                Cập nhật
                            </a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8" class="table-td text-center text-gray-500 py-8">
                            Không có đơn nào được phân công theo bộ lọc hiện tại.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-6">
        {{ $orders->links() }}
    </div>
</x-staff-layout>
