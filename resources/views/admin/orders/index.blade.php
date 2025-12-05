@extends('admin.layout')

@section('title', 'Quản lý đơn hàng')

@section('content')
<div class="mb-8">
    <h1 class="text-3xl font-bold text-gray-900">Quản lý đơn hàng</h1>
    <p class="text-gray-600 mt-2">Xem và quản lý tất cả đơn hàng trong hệ thống</p>
</div>

<!-- Stats Cards -->
<div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-6">
    <div class="bg-white rounded-lg shadow p-6">
        <p class="text-gray-600 text-sm font-semibold">Tổng đơn hàng</p>
        <p class="text-3xl font-bold text-gray-900 mt-2">{{ number_format($stats['total']) }}</p>
    </div>
    <div class="bg-yellow-50 rounded-lg shadow p-6 border border-yellow-200">
        <p class="text-yellow-800 text-sm font-semibold">Chưa phân công</p>
        <p class="text-3xl font-bold text-yellow-900 mt-2">{{ number_format($stats['unassigned']) }}</p>
    </div>
    <div class="bg-blue-50 rounded-lg shadow p-6 border border-blue-200">
        <p class="text-blue-800 text-sm font-semibold">Đang xử lý</p>
        <p class="text-3xl font-bold text-blue-900 mt-2">{{ number_format($stats['in_progress']) }}</p>
    </div>
    <div class="bg-green-50 rounded-lg shadow p-6 border border-green-200">
        <p class="text-green-800 text-sm font-semibold">Đã hoàn thành</p>
        <p class="text-3xl font-bold text-green-900 mt-2">{{ number_format($stats['delivered']) }}</p>
    </div>
</div>

<!-- Filters -->
<div class="bg-white rounded-lg shadow p-6 mb-6">
    <form action="{{ route('admin.orders.index') }}" method="GET">
        <div class="grid grid-cols-1 md:grid-cols-3 lg:grid-cols-6 gap-4 mb-4">
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">Tìm kiếm</label>
                <input 
                    type="text" 
                    name="search" 
                    value="{{ $filters['search'] }}"
                    placeholder="Mã đơn, SĐT..."
                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-orange-500 text-sm"
                >
            </div>

            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">Trạng thái</label>
                <select name="status" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-orange-500 text-sm">
                    <option value="">Tất cả</option>
                    @foreach($statusOptions as $value => $label)
                        <option value="{{ $value }}" {{ $filters['status'] === $value ? 'selected' : '' }}>{{ $label }}</option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">Nhân viên</label>
                <select name="assigned_to" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-orange-500 text-sm">
                    <option value="">Tất cả</option>
                    <option value="unassigned" {{ $filters['assigned_to'] === 'unassigned' ? 'selected' : '' }}>Chưa phân công</option>
                    @foreach($staffList as $staff)
                        <option value="{{ $staff->id }}" {{ $filters['assigned_to'] == $staff->id ? 'selected' : '' }}>{{ $staff->name }}</option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">Tuyến đường</label>
                <input 
                    type="text" 
                    name="route" 
                    value="{{ $filters['route'] }}"
                    placeholder="VD: HN-SG"
                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-orange-500 text-sm"
                >
            </div>

            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">Từ ngày</label>
                <input 
                    type="date" 
                    name="date_from" 
                    value="{{ $filters['date_from'] }}"
                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-orange-500 text-sm"
                >
            </div>

            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">Đến ngày</label>
                <input 
                    type="date" 
                    name="date_to" 
                    value="{{ $filters['date_to'] }}"
                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-orange-500 text-sm"
                >
            </div>
        </div>

        <div class="flex gap-2">
            <button type="submit" class="bg-orange-500 text-white px-6 py-2 rounded-lg font-semibold hover:bg-orange-600 transition text-sm">
                Lọc
            </button>
            @if($hasFilters)
                <a href="{{ route('admin.orders.index') }}" class="bg-gray-200 text-gray-700 px-4 py-2 rounded-lg hover:bg-gray-300 transition text-sm">
                    Xóa bộ lọc
                </a>
            @endif
        </div>
    </form>
</div>

<!-- Orders Table -->
<div class="bg-white rounded-lg shadow overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-4 py-3 text-left text-xs font-semibold text-gray-700 uppercase">Mã đơn</th>
                    <th class="px-4 py-3 text-left text-xs font-semibold text-gray-700 uppercase">Khách hàng</th>
                    <th class="px-4 py-3 text-left text-xs font-semibold text-gray-700 uppercase">Người nhận</th>
                    <th class="px-4 py-3 text-left text-xs font-semibold text-gray-700 uppercase">Tuyến</th>
                    <th class="px-4 py-3 text-left text-xs font-semibold text-gray-700 uppercase">Trạng thái</th>
                    <th class="px-4 py-3 text-left text-xs font-semibold text-gray-700 uppercase">Nhân viên</th>
                    <th class="px-4 py-3 text-left text-xs font-semibold text-gray-700 uppercase">Ngày giao</th>
                    <th class="px-4 py-3 text-left text-xs font-semibold text-gray-700 uppercase">Tổng tiền</th>
                    <th class="px-4 py-3 text-left text-xs font-semibold text-gray-700 uppercase">Thao tác</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200">
                @forelse($orders as $order)
                    <tr class="hover:bg-gray-50">
                        <td class="px-4 py-3 text-sm">
                            <a href="{{ route('admin.orders.show', $order) }}" class="font-semibold text-orange-600 hover:text-orange-700">
                                {{ $order->order_number }}
                            </a>
                            @if($order->shipment)
                                <p class="text-xs text-gray-500">{{ $order->shipment->tracking_number }}</p>
                            @endif
                        </td>
                        <td class="px-4 py-3 text-sm text-gray-900">
                            {{ $order->user?->name ?? 'Guest' }}
                        </td>
                        <td class="px-4 py-3 text-sm">
                            <p class="font-medium text-gray-900">{{ $order->receiver_name }}</p>
                            <p class="text-xs text-gray-500">{{ $order->receiver_phone }}</p>
                        </td>
                        <td class="px-4 py-3 text-sm">
                            <span class="px-2 py-1 bg-blue-100 text-blue-800 rounded text-xs font-semibold">
                                {{ $order->route_code ?? 'N/A' }}
                            </span>
                        </td>
                        <td class="px-4 py-3">
                            <x-order-status-badge :status="$order->status" />
                        </td>
                        <td class="px-4 py-3 text-sm">
                            @if($order->assignedStaff)
                                <div class="flex items-center justify-between">
                                    <p class="text-gray-900">{{ $order->assignedStaff->name }}</p>
                                    <form action="{{ route('admin.orders.assign-staff', $order) }}" method="POST" class="inline-block">
                                        @csrf
                                        <select name="assigned_to" onchange="if(confirm('Phân công lại cho nhân viên khác?')) this.form.submit()" class="text-xs px-2 py-1 border border-gray-300 rounded">
                                            <option value="">Đổi...</option>
                                            @foreach($staffList as $staff)
                                                <option value="{{ $staff->id }}" {{ $order->assigned_to == $staff->id ? 'selected' : '' }}>{{ $staff->name }}</option>
                                            @endforeach
                                        </select>
                                    </form>
                                </div>
                            @else
                                <form action="{{ route('admin.orders.assign-staff', $order) }}" method="POST" class="inline-block">
                                    @csrf
                                    <select name="assigned_to" onchange="if(this.value) this.form.submit()" class="text-xs px-2 py-1 border border-orange-300 rounded focus:ring-2 focus:ring-orange-500">
                                        <option value="">Phân công...</option>
                                        @foreach($staffList as $staff)
                                            <option value="{{ $staff->id }}">{{ $staff->name }}</option>
                                        @endforeach
                                    </select>
                                </form>
                            @endif
                        </td>
                        <td class="px-4 py-3 text-sm text-gray-600">
                            {{ $order->scheduled_date ? \Carbon\Carbon::parse($order->scheduled_date)->format('d/m/Y') : 'N/A' }}
                        </td>
                        <td class="px-4 py-3 text-sm font-semibold text-gray-900">
                            {{ number_format($order->total_amount, 0, ',', '.') }}₫
                        </td>
                        <td class="px-4 py-3">
                            <div class="flex gap-2">
                                <a href="{{ route('admin.orders.show', $order) }}" class="text-blue-600 hover:text-blue-800 text-xs font-semibold">
                                    Xem
                                </a>
                                <form action="{{ route('admin.orders.destroy', $order) }}" method="POST" class="inline" onsubmit="return confirm('Bạn có chắc muốn xóa đơn hàng này?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-600 hover:text-red-800 text-xs font-semibold">
                                        Xóa
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="9" class="px-4 py-8 text-center text-gray-500">
                            Không tìm thấy đơn hàng nào
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Pagination -->
    @if($orders->hasPages())
        <div class="px-6 py-4 border-t border-gray-200">
            {{ $orders->links() }}
        </div>
    @endif
</div>
@endsection
