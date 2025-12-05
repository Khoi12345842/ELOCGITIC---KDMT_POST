@extends('admin.layout')

@section('title', 'Chi tiết đơn hàng')

@section('content')
<div class="mb-8">
    <div class="flex justify-between items-center">
        <div>
            <h1 class="text-3xl font-bold text-gray-900">Chi tiết đơn hàng #{{ $order->order_number }}</h1>
            <p class="text-gray-600 mt-2">Xem và cập nhật thông tin đơn hàng</p>
        </div>
        <a href="{{ route('admin.orders.index') }}" class="bg-gray-200 text-gray-700 px-6 py-3 rounded-lg font-semibold hover:bg-gray-300 transition">
            ← Quay lại
        </a>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    <!-- Main Info -->
    <div class="lg:col-span-2 space-y-6">
        <!-- Order Status -->
        <div class="bg-white rounded-lg shadow p-6">
            <h2 class="text-xl font-bold text-gray-900 mb-4">Trạng thái đơn hàng</h2>
            <div class="flex items-center gap-4 mb-4">
                <x-order-status-badge :status="$order->status" class="text-lg" />
                <span class="text-gray-600">{{ $order->created_at->format('d/m/Y H:i') }}</span>
            </div>

            <!-- Quick Status Update Form -->
            <form action="{{ route('admin.orders.update-status', $order) }}" method="POST" class="border-t pt-4">
                @csrf
                @method('PUT')
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Cập nhật trạng thái</label>
                        <select name="status" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-orange-500">
                            @foreach(\App\Models\Order::STATUS_LABELS as $value => $label)
                                <option value="{{ $value }}" {{ $order->status === $value ? 'selected' : '' }}>{{ $label }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Ghi chú</label>
                        <input type="text" name="notes" placeholder="Ghi chú (tùy chọn)" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-orange-500">
                    </div>
                </div>
                <button type="submit" class="mt-4 bg-orange-500 text-white px-6 py-2 rounded-lg font-semibold hover:bg-orange-600 transition">
                    Cập nhật trạng thái
                </button>
            </form>
        </div>

        <!-- Shipment Info -->
        <div class="bg-white rounded-lg shadow p-6">
            <h2 class="text-xl font-bold text-gray-900 mb-4">Thông tin gói hàng</h2>
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <p class="text-sm text-gray-600">Mã vận đơn</p>
                    <p class="font-semibold text-gray-900">{{ $order->shipment?->tracking_number ?? 'N/A' }}</p>
                </div>
                <div>
                    <p class="text-sm text-gray-600">Tuyến đường</p>
                    <p class="font-semibold text-gray-900">{{ $order->route_code ?? 'N/A' }}</p>
                </div>
                <div>
                    <p class="text-sm text-gray-600">Khối lượng</p>
                    <p class="font-semibold text-gray-900">{{ $order->weight ?? 'N/A' }} kg</p>
                </div>
                <div>
                    <p class="text-sm text-gray-600">Kích thước</p>
                    <p class="font-semibold text-gray-900">{{ $order->dimensions ?? 'N/A' }}</p>
                </div>
                <div>
                    <p class="text-sm text-gray-600">Loại hàng hóa</p>
                    <p class="font-semibold text-gray-900">{{ $order->package_type ?? 'N/A' }}</p>
                </div>
                <div>
                    <p class="text-sm text-gray-600">Giá trị khai báo</p>
                    <p class="font-semibold text-gray-900">{{ number_format($order->declared_value ?? 0, 0, ',', '.') }}₫</p>
                </div>
            </div>
        </div>

        <!-- Sender & Receiver Info -->
        <div class="bg-white rounded-lg shadow p-6">
            <h2 class="text-xl font-bold text-gray-900 mb-4">Thông tin gửi nhận</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <h3 class="font-semibold text-gray-900 mb-2">Người gửi</h3>
                    <p class="text-gray-900">{{ $order->sender_name }}</p>
                    <p class="text-gray-600 text-sm">{{ $order->sender_phone }}</p>
                    <p class="text-gray-600 text-sm mt-1">{{ $order->sender_address }}</p>
                </div>
                <div>
                    <h3 class="font-semibold text-gray-900 mb-2">Người nhận</h3>
                    <p class="text-gray-900">{{ $order->receiver_name }}</p>
                    <p class="text-gray-600 text-sm">{{ $order->receiver_phone }}</p>
                    <p class="text-gray-600 text-sm mt-1">{{ $order->receiver_address }}</p>
                </div>
            </div>
        </div>

        <!-- Shipment History -->
        @if($order->shipment && $order->shipment->histories->count() > 0)
        <div class="bg-white rounded-lg shadow p-6">
            <h2 class="text-xl font-bold text-gray-900 mb-4">Lịch sử vận chuyển</h2>
            <div class="space-y-4">
                @foreach($order->shipment->histories as $history)
                    <div class="flex gap-4 border-l-2 border-orange-500 pl-4">
                        <div class="flex-1">
                            <div class="flex items-center gap-2 mb-1">
                                <x-order-status-badge :status="$history->status" />
                                <span class="text-sm text-gray-600">{{ $history->created_at->format('d/m/Y H:i') }}</span>
                            </div>
                            <p class="text-sm text-gray-900">{{ $history->location }}</p>
                            @if($history->notes)
                                <p class="text-sm text-gray-600 mt-1">{{ $history->notes }}</p>
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
        @endif

        <!-- Incident Reports -->
        @if($order->incidentReports && $order->incidentReports->count() > 0)
        <div class="bg-white rounded-lg shadow p-6">
            <h2 class="text-xl font-bold text-gray-900 mb-4">Báo cáo sự cố</h2>
            <div class="space-y-4">
                @foreach($order->incidentReports as $incident)
                    <div class="border border-red-200 rounded-lg p-4 bg-red-50">
                        <div class="flex justify-between items-start mb-2">
                            <span class="px-3 py-1 bg-red-100 text-red-800 rounded-full text-xs font-semibold">{{ $incident->type }}</span>
                            <span class="text-sm text-gray-600">{{ $incident->created_at->format('d/m/Y H:i') }}</span>
                        </div>
                        <p class="text-gray-900 font-medium">{{ $incident->description }}</p>
                        @if($incident->resolution_notes)
                            <p class="text-sm text-gray-600 mt-2">Giải quyết: {{ $incident->resolution_notes }}</p>
                        @endif
                    </div>
                @endforeach
            </div>
        </div>
        @endif
    </div>

    <!-- Sidebar -->
    <div class="space-y-6">
        <!-- Customer Info -->
        <div class="bg-white rounded-lg shadow p-6">
            <h2 class="text-xl font-bold text-gray-900 mb-4">Khách hàng</h2>
            @if($order->user)
                <div>
                    <p class="font-semibold text-gray-900">{{ $order->user->name }}</p>
                    <p class="text-sm text-gray-600">{{ $order->user->email }}</p>
                    <p class="text-sm text-gray-600">{{ $order->user->phone ?? 'N/A' }}</p>
                    <div class="mt-2">
                        @if($order->user->role === 'admin')
                            <span class="px-3 py-1 bg-orange-100 text-orange-800 rounded-full text-xs font-semibold">Admin</span>
                        @elseif($order->user->user_type === 'business')
                            <span class="px-3 py-1 bg-purple-100 text-purple-800 rounded-full text-xs font-semibold">Doanh nghiệp</span>
                        @else
                            <span class="px-3 py-1 bg-green-100 text-green-800 rounded-full text-xs font-semibold">Cá nhân</span>
                        @endif
                    </div>
                </div>
            @else
                <p class="text-gray-500">Khách vãng lai</p>
            @endif
        </div>

        <!-- Staff Assignment -->
        <div class="bg-white rounded-lg shadow p-6">
            <h2 class="text-xl font-bold text-gray-900 mb-4">Nhân viên phụ trách</h2>
            @if($order->assignedStaff)
                <div>
                    <p class="font-semibold text-gray-900">{{ $order->assignedStaff->name }}</p>
                    <p class="text-sm text-gray-600">{{ $order->assignedStaff->email }}</p>
                </div>
                <form action="{{ route('admin.orders.assign-staff', $order) }}" method="POST" class="mt-4">
                    @csrf
                    <select name="assigned_to" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-orange-500 mb-2">
                        <option value="">Chọn nhân viên khác...</option>
                        @foreach(\App\Models\User::where('role', 'staff')->get() as $staff)
                            <option value="{{ $staff->id }}" {{ $order->assigned_to == $staff->id ? 'selected' : '' }}>{{ $staff->name }}</option>
                        @endforeach
                    </select>
                    <button type="submit" class="w-full bg-orange-500 text-white px-4 py-2 rounded-lg font-semibold hover:bg-orange-600 transition">
                        Phân công lại
                    </button>
                </form>
            @else
                <p class="text-gray-500 mb-4">Chưa phân công</p>
                <form action="{{ route('admin.orders.assign-staff', $order) }}" method="POST">
                    @csrf
                    <select name="assigned_to" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-orange-500 mb-2">
                        <option value="">Chọn nhân viên...</option>
                        @foreach(\App\Models\User::where('role', 'staff')->get() as $staff)
                            <option value="{{ $staff->id }}">{{ $staff->name }}</option>
                        @endforeach
                    </select>
                    <button type="submit" class="w-full bg-orange-500 text-white px-4 py-2 rounded-lg font-semibold hover:bg-orange-600 transition">
                        Phân công
                    </button>
                </form>
            @endif
        </div>

        <!-- Payment Info -->
        <div class="bg-white rounded-lg shadow p-6">
            <h2 class="text-xl font-bold text-gray-900 mb-4">Thanh toán</h2>
            <div class="space-y-2">
                <div class="flex justify-between">
                    <span class="text-gray-600">Phí vận chuyển:</span>
                    <span class="font-semibold">{{ number_format($order->shipping_fee ?? 0, 0, ',', '.') }}₫</span>
                </div>
                @if($order->insurance_fee)
                <div class="flex justify-between">
                    <span class="text-gray-600">Phí bảo hiểm:</span>
                    <span class="font-semibold">{{ number_format($order->insurance_fee, 0, ',', '.') }}₫</span>
                </div>
                @endif
                @if($order->cod_amount)
                <div class="flex justify-between">
                    <span class="text-gray-600">COD:</span>
                    <span class="font-semibold">{{ number_format($order->cod_amount, 0, ',', '.') }}₫</span>
                </div>
                @endif
                <div class="flex justify-between pt-2 border-t border-gray-200">
                    <span class="text-gray-900 font-bold">Tổng cộng:</span>
                    <span class="text-orange-600 font-bold text-lg">{{ number_format($order->total_amount, 0, ',', '.') }}₫</span>
                </div>
            </div>
        </div>

        <!-- Danger Zone -->
        <div class="bg-white rounded-lg shadow p-6 border-2 border-red-200">
            <h2 class="text-xl font-bold text-red-700 mb-4">Vùng nguy hiểm</h2>
            <form action="{{ route('admin.orders.destroy', $order) }}" method="POST" onsubmit="return confirm('Bạn có CHẮC CHẮN muốn xóa đơn hàng này? Hành động này KHÔNG THỂ hoàn tác!')">
                @csrf
                @method('DELETE')
                <button type="submit" class="w-full bg-red-600 text-white px-4 py-2 rounded-lg font-semibold hover:bg-red-700 transition">
                    Xóa đơn hàng
                </button>
            </form>
        </div>
    </div>
</div>
@endsection
