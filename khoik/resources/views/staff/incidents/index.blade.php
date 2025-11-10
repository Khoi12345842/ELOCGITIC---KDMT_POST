<x-staff-layout title="Báo cáo sự cố">
    <div class="flex items-center justify-between mb-6">
        <div>
            <h1 class="text-3xl font-bold text-gray-900">Báo cáo sự cố giao hàng</h1>
            <p class="text-gray-600 mt-1">
                Ghi nhận và theo dõi các đơn giao thất bại để phối hợp xử lý nhanh chóng.
            </p>
        </div>
        <div class="flex items-center gap-2 text-sm text-gray-500">
            <span class="inline-flex items-center gap-2 px-3 py-2 rounded-full bg-red-100 text-red-700">
                <span class="w-2 h-2 rounded-full bg-red-500"></span>
                Trạng thái thất bại: {{ implode(', ', array_map(fn($status) => \App\Models\Order::STATUS_LABELS[$status] ?? $status, $failedStatuses)) }}
            </span>
        </div>
    </div>

    @if (session('success'))
        <div class="mb-6 p-4 rounded-lg bg-green-50 border border-green-200 text-green-700">
            {{ session('success') }}
        </div>
    @endif

    @if ($errors->any())
        <div class="mb-6 p-4 rounded-lg bg-red-50 border border-red-200 text-red-700">
            <p class="font-semibold mb-2">Không thể gửi báo cáo:</p>
            <ul class="list-disc list-inside space-y-1">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    @if ($failedOrders->isEmpty())
        <div class="bg-white border border-dashed border-gray-300 rounded-lg p-10 text-center">
            <div class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-green-100 text-green-600 mb-4">
                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
            </div>
            <h2 class="text-xl font-semibold text-gray-800 mb-2">Tuyệt vời! Hiện không có đơn thất bại.</h2>
            <p class="text-gray-600">Tiếp tục giữ vững tỉ lệ giao thành công nhé 🚀</p>
        </div>
    @else
        <div class="space-y-6">
            @foreach ($failedOrders as $order)
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                    <div class="flex flex-col md:flex-row md:items-start md:justify-between gap-4">
                        <div>
                            <div class="flex items-center gap-3">
                                <span class="text-sm uppercase tracking-wide text-gray-500">Đơn #{{ $order->order_number }}</span>
                                <x-order-status-badge :status="$order->status" />
                            </div>
                            <h2 class="text-xl font-semibold text-gray-900 mt-1">{{ $order->receiver_name }}</h2>
                            <p class="text-gray-600 text-sm mt-1">
                                {{ $order->receiver_address }} • {{ $order->receiver_city }} | {{ $order->receiver_phone }}
                            </p>
                            <p class="text-gray-500 text-xs mt-2">Cập nhật cuối: {{ $order->updated_at->format('H:i d/m/Y') }}</p>
                        </div>
                        <div class="bg-red-50 border border-red-100 rounded-lg px-4 py-3 text-sm text-red-700">
                            <p class="font-semibold flex items-center gap-2">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                                </svg>
                                Báo cáo sự cố ngay để phòng điều phối hỗ trợ.
                            </p>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mt-6">
                        <div>
                            <h3 class="text-sm font-semibold text-gray-700 uppercase tracking-wide mb-3">Gửi báo cáo mới</h3>
                            <form method="POST" action="{{ route('staff.incidents.store') }}" class="space-y-4">
                                @csrf
                                <input type="hidden" name="order_id" value="{{ $order->id }}">

                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Loại sự cố *</label>
                                    <select name="issue_type" class="form-select" required>
                                        <option value="">-- Chọn nguyên nhân thất bại --</option>
                                        @foreach ([
                                            'customer_absent' => 'Khách hàng không liên lạc / vắng mặt',
                                            'address_issue' => 'Sai địa chỉ / khó tìm',
                                            'package_damage' => 'Hàng hóa hư hỏng',
                                            'payment_issue' => 'Khách từ chối thanh toán',
                                            'system_issue' => 'Sự cố hệ thống',
                                            'other' => 'Khác'
                                        ] as $value => $label)
                                            <option value="{{ $value }}">{{ $label }}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Tóm tắt ngắn *</label>
                                    <input type="text" name="summary" class="form-input" placeholder="Ví dụ: Khách hẹn giao lại vào ngày mai" required>
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Chi tiết thêm</label>
                                    <textarea name="description" rows="3" class="form-textarea" placeholder="Ghi rõ tình huống, hướng xử lý đề xuất nếu có"></textarea>
                                </div>

                                <button type="submit" class="btn btn-primary w-full md:w-auto">
                                    Gửi báo cáo
                                </button>
                            </form>
                        </div>

                        <div>
                            <h3 class="text-sm font-semibold text-gray-700 uppercase tracking-wide mb-3">Lịch sử báo cáo</h3>
                            @if ($order->incidentReports->isEmpty())
                                <p class="text-sm text-gray-500">Chưa có báo cáo nào cho đơn này.</p>
                            @else
                                <div class="space-y-4 max-h-64 overflow-y-auto pr-1">
                                    @foreach ($order->incidentReports as $report)
                                        <div class="border border-gray-200 rounded-lg p-4 bg-gray-50">
                                            <div class="flex items-center justify-between text-xs text-gray-500">
                                                <span>{{ optional($report->reporter)->name ?? 'Hệ thống' }}</span>
                                                <span>{{ $report->created_at->format('H:i d/m/Y') }}</span>
                                            </div>
                                            <p class="text-sm font-semibold text-gray-800 mt-1">{{ $report->summary }}</p>
                                            <p class="text-xs uppercase tracking-wide text-indigo-600 mt-1">
                                                {{ \App\Models\IncidentReport::statusOptions()[$report->status] ?? $report->status }}
                                            </p>
                                            @if ($report->description)
                                                <p class="text-sm text-gray-600 mt-2">{{ $report->description }}</p>
                                            @endif
                                            @if ($report->resolution_notes)
                                                <div class="mt-2 text-xs text-green-600">
                                                    <span class="font-semibold">Ghi chú xử lý:</span> {{ $report->resolution_notes }}
                                                </div>
                                            @endif
                                        </div>
                                    @endforeach
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <div class="mt-6">
            {{ $failedOrders->links() }}
        </div>
    @endif
</x-staff-layout>
