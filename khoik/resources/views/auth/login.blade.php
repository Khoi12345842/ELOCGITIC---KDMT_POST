<x-layout title="Đăng nhập - FastShip">
    <div class="max-w-md mx-auto">
        <x-page-header 
            title="🔐 Đăng nhập" 
            subtitle="Chọn loại tài khoản và đăng nhập"
        />

        <x-card>
            <form method="POST" action="{{ route('login') }}" id="loginForm">
                @csrf

                <div class="space-y-6">
                    <!-- Chọn loại khách hàng -->
                    <div>
                        <label class="form-label">Bạn là khách hàng nào? *</label>
                        <div class="grid grid-cols-2 gap-4">
                            <label class="cursor-pointer">
                                <input type="radio" name="user_type" value="individual" 
                                       class="peer sr-only" 
                                       {{ old('user_type', 'individual') === 'individual' ? 'checked' : '' }}
                                       required>
                                <div class="p-4 border-2 border-gray-300 rounded-lg peer-checked:border-orange-600 peer-checked:bg-orange-50 transition-all hover:border-orange-400">
                                    <div class="text-center">
                                        <div class="text-3xl mb-2">👤</div>
                                        <div class="font-semibold text-gray-900">Cá nhân</div>
                                        <div class="text-xs text-gray-600 mt-1">Khách hàng thường</div>
                                    </div>
                                </div>
                            </label>

                            <label class="cursor-pointer">
                                <input type="radio" name="user_type" value="business" 
                                       class="peer sr-only" 
                                       {{ old('user_type') === 'business' ? 'checked' : '' }}
                                       required>
                                <div class="p-4 border-2 border-gray-300 rounded-lg peer-checked:border-blue-600 peer-checked:bg-blue-50 transition-all hover:border-blue-400">
                                    <div class="text-center">
                                        <div class="text-3xl mb-2">🏢</div>
                                        <div class="font-semibold text-gray-900">Doanh nghiệp</div>
                                        <div class="text-xs text-gray-600 mt-1">Ưu đãi đặc biệt</div>
                                    </div>
                                </div>
                            </label>
                        </div>
                        @error('user_type')
                            <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

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
                        <input type="password" name="password" required
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
                        <a href="#" class="text-sm text-orange-600 hover:underline">Quên mật khẩu?</a>
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
                        Chưa có tài khoản? 
                        <a href="{{ route('register') }}" class="text-orange-600 font-semibold hover:underline">Đăng ký ngay</a>
                    </p>
                </div>
            </form>
        </x-card>

        <!-- Ưu đãi cho khách hàng mới -->
        <div class="mt-6 grid grid-cols-1 md:grid-cols-2 gap-4">
            <div class="bg-gradient-to-br from-orange-50 to-orange-100 p-4 rounded-lg border border-orange-200">
                <div class="flex items-start gap-3">
                    <div class="text-3xl">👤</div>
                    <div>
                        <h4 class="font-semibold text-gray-900">Khách hàng cá nhân</h4>
                        <p class="text-sm text-gray-600 mt-1">Gửi hàng dễ dàng, thanh toán linh hoạt</p>
                    </div>
                </div>
            </div>

            <div class="bg-gradient-to-br from-blue-50 to-blue-100 p-4 rounded-lg border border-blue-200">
                <div class="flex items-start gap-3">
                    <div class="text-3xl">🏢</div>
                    <div>
                        <h4 class="font-semibold text-gray-900">Doanh nghiệp</h4>
                        <p class="text-sm text-gray-600 mt-1">Giảm 5-20%, lên đơn theo lô, liên kết shop</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-layout>
