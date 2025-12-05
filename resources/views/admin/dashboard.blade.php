@extends('admin.layout')

@section('title', 'Admin Dashboard')

@section('content')
<div class="mb-8">
    <h1 class="text-3xl font-bold text-gray-900">Dashboard Tổng Quan</h1>
    <p class="text-gray-600 mt-2">Thống kê và quản lý hệ thống</p>
</div>

<!-- Stats Cards -->
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
    <div class="bg-white rounded-lg shadow p-6">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-gray-600 text-sm font-semibold">Tổng khách hàng</p>
                <p class="text-3xl font-bold text-gray-900 mt-2">{{ $stats['total_users'] }}</p>
                <p class="text-xs text-gray-500 mt-1">
                    <span class="text-blue-600">{{ $stats['individual_users'] }}</span> cá nhân | 
                    <span class="text-purple-600">{{ $stats['business_users'] }}</span> doanh nghiệp
                </p>
            </div>
            <div class="bg-blue-100 p-3 rounded-full">
                <span class="text-2xl font-bold text-blue-600">U</span>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-lg shadow p-6">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-gray-600 text-sm font-semibold">Tổng nhân viên</p>
                <p class="text-3xl font-bold text-gray-900 mt-2">{{ $stats['total_staff'] }}</p>
                <p class="text-xs text-gray-500 mt-1">Đang hoạt động</p>
            </div>
            <div class="bg-green-100 p-3 rounded-full">
                <span class="text-2xl font-bold text-green-600">S</span>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-lg shadow p-6">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-gray-600 text-sm font-semibold">Tổng đơn hàng</p>
                <p class="text-3xl font-bold text-gray-900 mt-2">{{ $stats['total_orders'] }}</p>
                <p class="text-xs text-gray-500 mt-1">Tất cả đơn</p>
            </div>
            <div class="bg-orange-100 p-3 rounded-full">
                <span class="text-2xl font-bold text-orange-600">O</span>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-lg shadow p-6">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-gray-600 text-sm font-semibold">Doanh thu</p>
                <p class="text-3xl font-bold text-gray-900 mt-2">{{ number_format($stats['total_revenue'], 0, ',', '.') }}₫</p>
                <p class="text-xs text-gray-500 mt-1">Từ đơn đã giao</p>
            </div>
            <div class="bg-orange-100 p-3 rounded-full">
                <span class="text-2xl font-bold text-orange-600">₫</span>
            </div>
        </div>
    </div>
</div>

<!-- Order Stats -->
<div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
    <div class="bg-gradient-to-br from-yellow-50 to-yellow-100 rounded-lg shadow p-6 border border-yellow-200">
        <p class="text-yellow-800 font-semibold mb-2">Chờ xử lý</p>
        <p class="text-4xl font-bold text-yellow-900">{{ $stats['pending_orders'] }}</p>
    </div>

    <div class="bg-gradient-to-br from-blue-50 to-blue-100 rounded-lg shadow p-6 border border-blue-200">
        <p class="text-blue-800 font-semibold mb-2">Đang vận chuyển</p>
        <p class="text-4xl font-bold text-blue-900">{{ $stats['in_transit_orders'] }}</p>
    </div>

    <div class="bg-gradient-to-br from-green-50 to-green-100 rounded-lg shadow p-6 border border-green-200">
        <p class="text-green-800 font-semibold mb-2">Đã giao</p>
        <p class="text-4xl font-bold text-green-900">{{ $stats['delivered_orders'] }}</p>
    </div>

    <div class="bg-gradient-to-br from-red-50 to-red-100 rounded-lg shadow p-6 border border-red-200">
        <p class="text-red-800 font-semibold mb-2">Thất bại</p>
        <p class="text-4xl font-bold text-red-900">{{ $stats['failed_orders'] }}</p>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
    <!-- Top Staff -->
    <div class="bg-white rounded-lg shadow p-6">
        <h2 class="text-xl font-bold text-gray-900 mb-4">Top Nhân Viên</h2>
        <div class="space-y-3">
            @forelse($topStaff as $staff)
                <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                    <div>
                        <p class="font-semibold text-gray-900">{{ $staff->name }}</p>
                        <p class="text-sm text-gray-600">{{ $staff->email }}</p>
                    </div>
                    <div class="text-right">
                        <p class="text-lg font-bold text-green-600">{{ $staff->completed_orders }}</p>
                        <p class="text-xs text-gray-500">đơn hoàn thành</p>
                    </div>
                </div>
            @empty
                <p class="text-gray-500 text-center py-4">Chưa có dữ liệu</p>
            @endforelse
        </div>
    </div>

    <!-- Top Customers -->
    <div class="bg-white rounded-lg shadow p-6">
        <h2 class="text-xl font-bold text-gray-900 mb-4">Top Khách Hàng</h2>
        <div class="space-y-3">
            @forelse($topCustomers as $customer)
                <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                    <div>
                        <p class="font-semibold text-gray-900">{{ $customer->name }}</p>
                        <p class="text-sm text-gray-600">{{ $customer->email }}</p>
                    </div>
                    <div class="text-right">
                        <p class="text-lg font-bold text-orange-600">{{ number_format($customer->total_spent ?? 0, 0, ',', '.') }}₫</p>
                        <p class="text-xs text-gray-500">tổng chi tiêu</p>
                    </div>
                </div>
            @empty
                <p class="text-gray-500 text-center py-4">Chưa có dữ liệu</p>
            @endforelse
        </div>
    </div>
</div>

<!-- Recent Orders -->
<div class="bg-white rounded-lg shadow">
    <div class="p-6 border-b border-gray-200">
        <h2 class="text-xl font-bold text-gray-900">Đơn hàng gần đây</h2>
    </div>
    <div class="overflow-x-auto">
        <table class="w-full">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700 uppercase">Mã đơn</th>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700 uppercase">Khách hàng</th>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700 uppercase">Người nhận</th>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700 uppercase">Trạng thái</th>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700 uppercase">Nhân viên</th>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700 uppercase">Tổng tiền</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200">
                @forelse($recentOrders as $order)
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4 text-sm font-medium text-gray-900">{{ $order->order_number }}</td>
                        <td class="px-6 py-4 text-sm text-gray-600">
                            {{ $order->user?->name ?? 'Guest' }}
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-600">{{ $order->receiver_name }}</td>
                        <td class="px-6 py-4">
                            <x-order-status-badge :status="$order->status" />
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-600">
                            {{ $order->assignedStaff?->name ?? 'Chưa phân công' }}
                        </td>
                        <td class="px-6 py-4 text-sm font-semibold text-gray-900">
                            {{ number_format($order->total_amount, 0, ',', '.') }}₫
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="px-6 py-8 text-center text-gray-500">Chưa có đơn hàng nào</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
