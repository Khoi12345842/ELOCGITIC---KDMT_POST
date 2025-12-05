<x-layout title="GENZ EXPRESS - Trang chủ">
    <div class="bg-gradient-to-r from-orange-500 to-orange-600 text-white py-20">
        <div class="max-w-4xl mx-auto px-4 text-center">
            <h1 class="text-5xl font-bold mb-4">GENZ EXPRESS</h1>
            <p class="text-lg mb-6">Dịch vụ chuyển phát nhanh - Giao hàng toàn quốc</p>
            @guest
                <div class="flex gap-4 justify-center">
                    <a href="{{ route('register') }}" class="bg-white text-orange-600 px-6 py-2 rounded-lg font-semibold hover:bg-gray-100 transition">Đăng ký</a>
                    <a href="{{ route('login') }}" class="bg-orange-700 text-white px-6 py-2 rounded-lg font-semibold hover:bg-orange-800 transition">Đăng nhập</a>
                </div>
            @endguest
            @auth
                <p class="text-lg">Xin chào, <span class="font-semibold">{{ auth()->user()->name }}</span>!</p>
            @endauth
        </div>
    </div>

    <div class="max-w-6xl mx-auto px-4 py-12">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-12">
                <a href="{{ auth()->check() ? route('orders.create.individual') : route('login') }}" class="bg-white rounded-xl shadow-md p-6 hover:shadow-xl transition transform hover:-translate-y-1">
                    <h3 class="text-xl font-bold text-gray-800 mb-1">Tạo đơn</h3>
                    <p class="text-gray-600 text-sm">Gửi hàng nhanh chóng</p>
                </a>
                <a href="{{ route('orders.track') }}" class="bg-white rounded-xl shadow-md p-6 hover:shadow-xl transition transform hover:-translate-y-1">
                    <h3 class="text-xl font-bold text-gray-800 mb-1">Tra cứu</h3>
                    <p class="text-gray-600 text-sm">Theo dõi trạng thái đơn</p>
                </a>
                <a href="{{ auth()->check() ? route('orders.index') : route('login') }}" class="bg-white rounded-xl shadow-md p-6 hover:shadow-xl transition transform hover:-translate-y-1">
                    <h3 class="text-xl font-bold text-gray-800 mb-1">Đơn hàng</h3>
                    <p class="text-gray-600 text-sm">Quản lý đơn của bạn</p>
                </a>
                <a href="{{ route('post-offices.index') }}" class="bg-white rounded-xl shadow-md p-6 hover:shadow-xl transition transform hover:-translate-y-1">
                    <h3 class="text-xl font-bold text-gray-800 mb-1">Bưu cục</h3>
                    <p class="text-gray-600 text-sm">Tìm điểm gửi gần nhất</p>
                </a>
            </div>

            @auth
                @if(auth()->user()->isBusiness())
                    <div class="bg-orange-50 border border-orange-200 rounded-xl p-6 mb-12 flex flex-col md:flex-row gap-4 md:items-center md:justify-between">
                        <div>
                            <h3 class="text-2xl font-bold text-gray-800 mb-2">Tạo đơn hàng loạt</h3>
                            <p class="text-gray-600">Dành cho khách doanh nghiệp - xử lý nhiều đơn cùng lúc</p>
                        </div>
                        <a href="{{ route('orders.create.bulk') }}" class="bg-orange-600 text-white px-6 py-3 rounded-lg font-semibold hover:bg-orange-700 transition">Bắt đầu ngay</a>
                    </div>
                @endif

                <div class="bg-white rounded-xl shadow-md p-6">
                    <h3 class="text-2xl font-bold text-gray-800 mb-6">Thống kê của bạn</h3>
                    <div class="grid grid-cols-2 md:grid-cols-4 gap-6 text-center">
                        <div>
                            <p class="text-3xl font-bold text-gray-800">{{ $stats['total'] }}</p>
                            <p class="text-sm text-gray-500">Tổng đơn</p>
                        </div>
                        <div>
                            <p class="text-3xl font-bold text-blue-600">{{ $stats['pending'] }}</p>
                            <p class="text-sm text-gray-500">Đang xử lý</p>
                        </div>
                        <div>
                            <p class="text-3xl font-bold text-orange-500">{{ $stats['in_transit'] }}</p>
                            <p class="text-sm text-gray-500">Đang giao</p>
                        </div>
                        <div>
                            <p class="text-3xl font-bold text-green-600">{{ $stats['delivered'] }}</p>
                            <p class="text-sm text-gray-500">Đã giao</p>
                        </div>
                    </div>
                </div>
            @endauth
        </div>
    </div>
</x-layout>
