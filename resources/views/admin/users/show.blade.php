@extends('admin.layout')

@section('title', 'Chi tiết người dùng')

@section('content')
<div class="mb-8">
    <a href="{{ route('admin.users.index') }}" class="text-purple-600 hover:text-purple-800 font-semibold mb-4 inline-block">
        ← Quay lại danh sách
    </a>
    <h1 class="text-3xl font-bold text-gray-900">👤 Chi tiết người dùng</h1>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    <!-- User Info -->
    <div class="lg:col-span-2 bg-white rounded-lg shadow p-6">
        <div class="flex justify-between items-start mb-6">
            <div>
                <h2 class="text-2xl font-bold text-gray-900">{{ $user->name }}</h2>
                <p class="text-gray-600 mt-1">{{ $user->email }}</p>
            </div>
            <div class="flex gap-2">
                <a href="{{ route('admin.users.edit', $user) }}" class="bg-purple-600 text-white px-4 py-2 rounded-lg hover:bg-purple-700 transition">
                    ✏️ Chỉnh sửa
                </a>
            </div>
        </div>

        <div class="grid grid-cols-2 gap-4 mb-6">
            <div>
                <p class="text-sm text-gray-600">Vai trò</p>
                <p class="font-semibold text-gray-900 mt-1">
                    @if($user->role === 'admin')
                        <span class="px-3 py-1 bg-purple-100 text-purple-800 rounded-full text-sm">👨‍💼 Admin</span>
                    @elseif($user->role === 'staff')
                        <span class="px-3 py-1 bg-blue-100 text-blue-800 rounded-full text-sm">👨‍💼 Nhân viên</span>
                    @else
                        <span class="px-3 py-1 bg-gray-100 text-gray-800 rounded-full text-sm">👤 Khách hàng</span>
                    @endif
                </p>
            </div>
            <div>
                <p class="text-sm text-gray-600">Loại tài khoản</p>
                <p class="font-semibold text-gray-900 mt-1">
                    @if($user->user_type === 'business')
                        <span class="px-3 py-1 bg-orange-100 text-orange-800 rounded-full text-sm">🏢 Doanh nghiệp</span>
                    @else
                        <span class="px-3 py-1 bg-green-100 text-green-800 rounded-full text-sm">👤 Cá nhân</span>
                    @endif
                </p>
            </div>
            <div>
                <p class="text-sm text-gray-600">Số điện thoại</p>
                <p class="font-semibold text-gray-900 mt-1">{{ $user->phone ?? 'Chưa có' }}</p>
            </div>
            <div>
                <p class="text-sm text-gray-600">Ngày tạo</p>
                <p class="font-semibold text-gray-900 mt-1">{{ $user->created_at->format('d/m/Y H:i') }}</p>
            </div>
        </div>

        @if($user->address)
            <div class="mb-4">
                <p class="text-sm text-gray-600">Địa chỉ</p>
                <p class="font-semibold text-gray-900 mt-1">{{ $user->address }}</p>
            </div>
        @endif

        @if($user->isBusiness())
            <div class="bg-orange-50 p-4 rounded-lg">
                <h3 class="font-semibold text-gray-900 mb-3">🏢 Thông tin doanh nghiệp</h3>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <p class="text-sm text-gray-600">Tên công ty</p>
                        <p class="font-semibold text-gray-900 mt-1">{{ $user->company_name ?? 'Chưa có' }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600">Mã số thuế</p>
                        <p class="font-semibold text-gray-900 mt-1">{{ $user->tax_code ?? 'Chưa có' }}</p>
                    </div>
                    @if($user->company_address)
                        <div class="col-span-2">
                            <p class="text-sm text-gray-600">Địa chỉ công ty</p>
                            <p class="font-semibold text-gray-900 mt-1">{{ $user->company_address }}</p>
                        </div>
                    @endif
                    @if($user->shop_name)
                        <div>
                            <p class="text-sm text-gray-600">Shop liên kết</p>
                            <p class="font-semibold text-gray-900 mt-1">{{ $user->shop_name }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-600">Nền tảng</p>
                            <p class="font-semibold text-gray-900 mt-1">{{ $user->shop_platform }}</p>
                        </div>
                    @endif
                </div>
            </div>
        @endif
    </div>

    <!-- Stats -->
    <div class="space-y-6">
        <div class="bg-white rounded-lg shadow p-6">
            <h3 class="font-semibold text-gray-900 mb-4">📊 Thống kê</h3>
            <div class="space-y-4">
                @if($user->isStaff())
                    <div>
                        <p class="text-sm text-gray-600">Đơn được phân công</p>
                        <p class="text-2xl font-bold text-blue-600">{{ $stats['assigned_orders'] }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600">Đơn hoàn thành</p>
                        <p class="text-2xl font-bold text-green-600">{{ $stats['completed_orders'] }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600">Đơn chờ xử lý</p>
                        <p class="text-2xl font-bold text-yellow-600">{{ $stats['pending_orders'] }}</p>
                    </div>
                @else
                    <div>
                        <p class="text-sm text-gray-600">Tổng đơn hàng</p>
                        <p class="text-2xl font-bold text-blue-600">{{ $stats['total_orders'] }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600">Đơn hoàn thành</p>
                        <p class="text-2xl font-bold text-green-600">{{ $stats['completed_orders'] }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600">Tổng chi tiêu</p>
                        <p class="text-2xl font-bold text-purple-600">{{ number_format($stats['total_spent'], 0, ',', '.') }}₫</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

<!-- Recent Orders -->
@if($user->orders->isNotEmpty())
    <div class="mt-6 bg-white rounded-lg shadow">
        <div class="p-6 border-b border-gray-200">
            <h3 class="text-xl font-bold text-gray-900">📦 Đơn hàng gần đây</h3>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700 uppercase">Mã đơn</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700 uppercase">Người nhận</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700 uppercase">Trạng thái</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700 uppercase">Tổng tiền</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700 uppercase">Ngày tạo</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @foreach($user->orders->take(10) as $order)
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4 text-sm font-medium text-gray-900">{{ $order->order_number }}</td>
                            <td class="px-6 py-4 text-sm text-gray-600">{{ $order->receiver_name }}</td>
                            <td class="px-6 py-4">
                                <x-order-status-badge :status="$order->status" />
                            </td>
                            <td class="px-6 py-4 text-sm font-semibold text-gray-900">
                                {{ number_format($order->total_amount, 0, ',', '.') }}₫
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-600">{{ $order->created_at->format('d/m/Y') }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
@endif
@endsection
