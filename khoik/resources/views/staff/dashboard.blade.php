<x-staff-layout title="Dashboard - Nhân viên vận chuyển">
    <!-- Header -->
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-900">Xin chào, {{ auth()->user()->name }}!</h1>
        <p class="text-gray-600 mt-1">Chào mừng bạn đến với hệ thống quản lý vận chuyển</p>
    </div>

    <!-- Thống kê -->
    <div class="grid grid-cols-1 md:grid-cols-5 gap-6 mb-8">
        <div class="bg-white rounded-lg shadow-md p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600">Tổng đơn</p>
                    <p class="text-3xl font-bold text-gray-900 mt-2">{{ $stats['total'] }}</p>
                </div>
                <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center">
                    <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"/>
                    </svg>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow-md p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600">Chờ xử lý</p>
                    <p class="text-3xl font-bold text-yellow-600 mt-2">{{ $stats['pending'] }}</p>
                </div>
                <div class="w-12 h-12 bg-yellow-100 rounded-lg flex items-center justify-center">
                    <svg class="w-6 h-6 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow-md p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600">Đang giao</p>
                    <p class="text-3xl font-bold text-orange-600 mt-2">{{ $stats['in_transit'] }}</p>
                </div>
                <div class="w-12 h-12 bg-orange-100 rounded-lg flex items-center justify-center">
                    <svg class="w-6 h-6 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"/>
                    </svg>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow-md p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600">Đã giao</p>
                    <p class="text-3xl font-bold text-green-600 mt-2">{{ $stats['delivered'] }}</p>
                </div>
                <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center">
                    <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow-md p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600">Thất bại</p>
                    <p class="text-3xl font-bold text-red-600 mt-2">{{ $stats['failed'] }}</p>
                </div>
                <div class="w-12 h-12 bg-red-100 rounded-lg flex items-center justify-center">
                    <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </div>
            </div>
        </div>
    </div>

    <!-- Các module chức năng -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        <!-- Module 1: Quản lý đơn hàng -->
        <a href="{{ route('staff.orders.index') }}" class="card p-6 hover:shadow-xl transition-all transform hover:-translate-y-1">
            <div class="flex items-center gap-4 mb-4">
                <div class="w-14 h-14 bg-blue-100 rounded-lg flex items-center justify-center">
                    <svg class="w-8 h-8 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                    </svg>
                </div>
                <div>
                    <h3 class="text-lg font-bold text-gray-900">Đơn được phân công</h3>
                    <p class="text-sm text-gray-600">Xem và quản lý</p>
                </div>
            </div>
            <p class="text-gray-600 text-sm">Xem danh sách đơn được giao, lọc theo trạng thái, tuyến và ngày giao.</p>
        </a>

        <!-- Module 2: Cập nhật trạng thái -->
        <a href="{{ route('staff.orders.index') }}" class="card p-6 hover:shadow-xl transition-all transform hover:-translate-y-1">
            <div class="flex items-center gap-4 mb-4">
                <div class="w-14 h-14 bg-orange-100 rounded-lg flex items-center justify-center">
                    <svg class="w-8 h-8 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                    </svg>
                </div>
                <div>
                    <h3 class="text-lg font-bold text-gray-900">Cập nhật trạng thái</h3>
                    <p class="text-sm text-green-600">✓ Đang hoạt động</p>
                </div>
            </div>
            <p class="text-gray-600 text-sm">Cập nhật vị trí, trạng thái giao hàng và thời gian thực tế cho đơn được phân công.</p>
        </a>

        <!-- Module 3: Báo cáo sự cố -->
        <a href="{{ route('staff.incidents.index') }}" class="card p-6 hover:shadow-xl transition-all transform hover:-translate-y-1">
            <div class="flex items-center gap-4 mb-4">
                <div class="w-14 h-14 bg-red-100 rounded-lg flex items-center justify-center">
                    <svg class="w-8 h-8 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                    </svg>
                </div>
                <div>
                    <h3 class="text-lg font-bold text-gray-900">Báo cáo sự cố</h3>
                    <p class="text-sm text-red-600">Theo dõi & xử lý đơn thất bại</p>
                </div>
            </div>
            <p class="text-gray-600 text-sm">Tổng hợp đơn giao không thành công, gửi báo cáo chi tiết để điều phối hỗ trợ.</p>
        </a>

        <!-- Module 4: Tìm hiểu GenZExpress -->
        <a href="{{ route('staff.info.genz') }}" class="card p-6 hover:shadow-xl transition-all transform hover:-translate-y-1">
            <div class="flex items-center gap-4 mb-4">
                <div class="w-14 h-14 bg-purple-100 rounded-lg flex items-center justify-center">
                    <svg class="w-8 h-8 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8m-18 8h18"/>
                    </svg>
                </div>
                <div>
                    <h3 class="text-lg font-bold text-gray-900">Tìm hiểu GenZExpress</h3>
                    <p class="text-sm text-purple-600">Thương hiệu giao vận thế hệ mới</p>
                </div>
            </div>
            <p class="text-gray-600 text-sm">Khám phá quy trình, giá trị và câu chuyện phát triển của GenZExpress ngay trong hệ thống.</p>
        </a>

        <!-- Module 5: Trung tâm thông tin -->
        <a href="{{ route('staff.info') }}" class="card p-6 hover:shadow-xl transition-all transform hover:-translate-y-1">
            <div class="flex items-center gap-4 mb-4">
                <div class="w-14 h-14 bg-teal-100 rounded-lg flex items-center justify-center">
                    <svg class="w-8 h-8 text-teal-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M12 18a6 6 0 110-12 6 6 0 010 12z"/>
                    </svg>
                </div>
                <div>
                    <h3 class="text-lg font-bold text-gray-900">Trung tâm thông tin</h3>
                    <p class="text-sm text-teal-600">Quy định & lưu ý nội bộ</p>
                </div>
            </div>
            <p class="text-gray-600 text-sm">Cập nhật điều khoản, quy trình xử lý tình huống và kênh hỗ trợ dành cho nhân viên.</p>
        </a>
    </div>

    <!-- Hướng dẫn nhanh -->
    <div class="mt-8 bg-orange-50 border border-orange-200 rounded-lg p-6">
        <h3 class="text-lg font-bold text-gray-900 mb-3">📌 Hướng dẫn sử dụng</h3>
        <ul class="space-y-2 text-sm text-gray-700">
            <li>• Quản lý các đơn đã giao thất bại và gửi báo cáo ngay tại module "Báo cáo sự cố"</li>
            <li>• Nắm rõ quy định, điều khoản và kênh hỗ trợ trong mục "Trung tâm thông tin"</li>
            <li>• Theo dõi, cập nhật trạng thái giao hàng của bạn tại module "Cập nhật trạng thái"</li>
            <li>• Theo dõi cập nhật mới từ GenZExpress để nắm bắt xu hướng ngành</li>
        </ul>
    </div>
</x-staff-layout>
