<x-staff-layout>
    <div class="min-h-screen bg-gray-50 py-8">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Header -->
            <div class="mb-6">
                <a href="{{ route('staff.orders.index') }}" class="text-orange-600 hover:text-orange-700 flex items-center gap-2 mb-4">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                    </svg>
                    Quay lại danh sách đơn hàng
                </a>
                <h1 class="text-3xl font-bold text-gray-900">Cập nhật trạng thái đơn hàng</h1>
                <p class="mt-2 text-gray-600">Mã đơn: <span class="font-semibold text-orange-600">{{ $order->order_number }}</span></p>
            </div>

            @if(session('success'))
                <div class="mb-6 bg-green-50 border border-green-200 text-green-800 px-4 py-3 rounded-lg flex items-center gap-3">
                    <svg class="w-5 h-5 text-green-500" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                    </svg>
                    <span>{{ session('success') }}</span>
                </div>
            @endif

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                <!-- Form cập nhật trạng thái -->
                <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                    <h2 class="text-xl font-semibold text-gray-900 mb-4">Cập nhật mới</h2>
                    
                    <form action="{{ route('staff.orders.status.update', $order) }}" method="POST" class="space-y-4">
                        @csrf
                        @method('PUT')

                        <!-- Trạng thái hiện tại -->
                        <div class="bg-orange-50 border border-orange-200 rounded-lg p-4">
                            <p class="text-sm text-gray-600">Trạng thái hiện tại:</p>
                            <p class="text-lg font-semibold text-orange-600">{{ $order->status_label }}</p>
                        </div>

                        <!-- Chọn trạng thái mới -->
                        <div>
                            <label for="status" class="block text-sm font-medium text-gray-700 mb-2">
                                Trạng thái mới <span class="text-red-500">*</span>
                            </label>
                            <select name="status" id="status" required
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-orange-500">
                                <option value="">-- Chọn trạng thái --</option>
                                <option value="pending" {{ old('status', $order->status) == 'pending' ? 'selected' : '' }}>
                                    Chờ xử lý
                                </option>
                                <option value="confirmed" {{ old('status', $order->status) == 'confirmed' ? 'selected' : '' }}>
                                    Đã xác nhận
                                </option>
                                <option value="picked_up" {{ old('status', $order->status) == 'picked_up' ? 'selected' : '' }}>
                                    Đã lấy hàng
                                </option>
                                <option value="in_transit" {{ old('status', $order->status) == 'in_transit' ? 'selected' : '' }}>
                                    Đang vận chuyển
                                </option>
                                <option value="out_delivery" {{ old('status', $order->status) == 'out_delivery' ? 'selected' : '' }}>
                                    Đang giao hàng
                                </option>
                                <option value="delivered" {{ old('status', $order->status) == 'delivered' ? 'selected' : '' }}>
                                    Đã giao hàng
                                </option>
                                <option value="cancelled" {{ old('status', $order->status) == 'cancelled' ? 'selected' : '' }}>
                                    Đã hủy
                                </option>
                                <option value="returned" {{ old('status', $order->status) == 'returned' ? 'selected' : '' }}>
                                    Hoàn trả
                                </option>
                            </select>
                            @error('status')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Vị trí hiện tại -->
                        <div>
                            <label for="location" class="block text-sm font-medium text-gray-700 mb-2">
                                Vị trí hiện tại
                            </label>
                            <input type="text" name="location" id="location" value="{{ old('location') }}"
                                placeholder="VD: Bưu cục Hoàn Kiếm, Hà Nội"
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-orange-500">
                            @error('location')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Ghi chú -->
                        <div>
                            <label for="notes" class="block text-sm font-medium text-gray-700 mb-2">
                                Ghi chú
                            </label>
                            <textarea name="notes" id="notes" rows="4"
                                placeholder="Nhập ghi chú về cập nhật này..."
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-orange-500">{{ old('notes') }}</textarea>
                            @error('notes')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Buttons -->
                        <div class="flex gap-3 pt-4">
                            <button type="submit"
                                class="flex-1 bg-orange-600 text-white px-6 py-3 rounded-lg hover:bg-orange-700 font-semibold transition-colors">
                                Cập nhật trạng thái
                            </button>
                            <a href="{{ route('staff.orders.index') }}"
                                class="px-6 py-3 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 font-semibold transition-colors">
                                Hủy
                            </a>
                        </div>
                    </form>
                </div>

                <!-- Thông tin đơn hàng -->
                <div class="space-y-6">
                    <!-- Thông tin cơ bản -->
                    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                        <h2 class="text-xl font-semibold text-gray-900 mb-4">Thông tin đơn hàng</h2>
                        <dl class="space-y-3">
                            <div class="flex justify-between">
                                <dt class="text-gray-600">Người nhận:</dt>
                                <dd class="font-semibold text-gray-900">{{ $order->receiver_name }}</dd>
                            </div>
                            <div class="flex justify-between">
                                <dt class="text-gray-600">SĐT:</dt>
                                <dd class="font-semibold text-gray-900">{{ $order->receiver_phone }}</dd>
                            </div>
                            <div class="flex flex-col gap-1">
                                <dt class="text-gray-600">Địa chỉ giao:</dt>
                                <dd class="font-semibold text-gray-900">{{ $order->receiver_address }}, {{ $order->receiver_city }}</dd>
                            </div>
                            <div class="flex justify-between">
                                <dt class="text-gray-600">Tuyến đường:</dt>
                                <dd class="font-semibold text-gray-900">{{ $order->route_code ?? 'Chưa có' }}</dd>
                            </div>
                            <div class="flex justify-between">
                                <dt class="text-gray-600">Ngày giao:</dt>
                                <dd class="font-semibold text-gray-900">
                                    {{ $order->scheduled_date ? $order->scheduled_date->format('d/m/Y') : 'Chưa có' }}
                                </dd>
                            </div>
                        </dl>
                    </div>

                    <!-- Lịch sử cập nhật -->
                    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                        <h2 class="text-xl font-semibold text-gray-900 mb-4">Lịch sử cập nhật</h2>
                        
                        @if($histories->count() > 0)
                            <div class="space-y-4">
                                @foreach($histories as $history)
                                    <div class="border-l-4 border-orange-500 pl-4 py-2">
                                        <div class="flex items-center justify-between mb-1">
                                            <span class="font-semibold text-gray-900">
                                                {{ \App\Models\Order::STATUS_LABELS[$history->status] ?? $history->status }}
                                            </span>
                                            <span class="text-sm text-gray-500">
                                                {{ $history->happened_at->format('d/m/Y H:i') }}
                                            </span>
                                        </div>
                                        
                                        @if($history->location)
                                            <p class="text-sm text-gray-600 flex items-center gap-1">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                                                </svg>
                                                {{ $history->location }}
                                            </p>
                                        @endif
                                        
                                        @if($history->notes)
                                            <p class="text-sm text-gray-600 mt-1">{{ $history->notes }}</p>
                                        @endif
                                        
                                        @if($history->updatedByUser)
                                            <p class="text-xs text-gray-500 mt-1">
                                                Cập nhật bởi: {{ $history->updatedByUser->name }}
                                            </p>
                                        @endif
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <p class="text-gray-500 text-center py-4">Chưa có lịch sử cập nhật</p>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-staff-layout>
