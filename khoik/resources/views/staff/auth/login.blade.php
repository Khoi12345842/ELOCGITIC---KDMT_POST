<x-layout title="Đăng nhập nhân viên - GENZ EXPRESS">
    <div class="max-w-md mx-auto">
        <x-page-header 
            title="Đăng nhập nhân viên" 
            subtitle="Sử dụng tài khoản được cấp để truy cập hệ thống"
        />

        <x-card>
            <form method="POST" action="{{ route('staff.login.submit') }}">
                @csrf

                <div class="space-y-6">
                    <div>
                        <label class="form-label">Email</label>
                        <input type="email" name="email" value="{{ old('email') }}" required autofocus
                               class="form-input" placeholder="example@email.com">
                        @error('email')
                            <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="form-label">Mật khẩu</label>
                        <input type="password" name="password" value="{{ old('password') }}" required
                               class="form-input" placeholder="••••••••">
                        @error('password')
                            <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="flex items-center justify-between">
                        <label class="flex items-center gap-2 cursor-pointer">
                            <input type="checkbox" name="remember" class="rounded">
                            <span class="text-sm text-gray-700">Ghi nhớ đăng nhập</span>
                        </label>
                        <a href="#" class="text-sm text-orange-600 hover:underline">Liên hệ quản trị</a>
                    </div>
                </div>

                <button type="submit" class="btn btn-primary w-full btn-lg mt-6">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1"/>
                    </svg>
                    Đăng nhập
                </button>

                <div class="text-center mt-6">
                    <p class="text-gray-600">
                        Không phải nhân viên? 
                        <a href="{{ route('login') }}" class="text-orange-600 font-semibold hover:underline">Về trang khách hàng</a>
                    </p>
                </div>
            </form>
        </x-card>

        <!-- Ưu đãi cho nhân viên -->
        <div class="mt-6 grid grid-cols-1 md:grid-cols-2 gap-4">
            <div class="bg-gradient-to-br from-orange-50 to-orange-100 p-4 rounded-lg border border-orange-200">
                <div class="flex items-start gap-3">
                    <div class="text-3xl">�</div>
                    <div>
                        <h4 class="font-semibold text-gray-900">Quản lý đơn hàng</h4>
                        <p class="text-sm text-gray-600 mt-1">Xem và cập nhật trạng thái đơn được phân công</p>
                    </div>
                </div>
            </div>

            <div class="bg-gradient-to-br from-blue-50 to-blue-100 p-4 rounded-lg border border-blue-200">
                <div class="flex items-start gap-3">
                    <div class="text-3xl">�</div>
                    <div>
                        <h4 class="font-semibold text-gray-900">Vận chuyển</h4>
                        <p class="text-sm text-gray-600 mt-1">Theo dõi và báo cáo tiến độ giao hàng</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-layout>