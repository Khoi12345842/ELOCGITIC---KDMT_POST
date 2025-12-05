<x-layout title="Đăng ký tài khoản - FastShip">
    <div class="max-w-2xl mx-auto">
        <x-page-header 
            title="📝 Đăng ký tài khoản" 
            subtitle="Tạo tài khoản để bắt đầu sử dụng dịch vụ FastShip"
        />

        <x-card>
            <form method="POST" action="{{ route('register') }}" id="registerForm">
                @csrf

                <!-- Chọn loại tài khoản -->
                <div class="mb-6">
                    <label class="form-label">Loại tài khoản *</label>
                    <div class="grid grid-cols-2 gap-4">
                        <label class="cursor-pointer">
                            <input type="radio" name="user_type" value="individual" 
                                   class="peer sr-only" 
                                   {{ old('user_type', 'individual') === 'individual' ? 'checked' : '' }}
                                   onchange="toggleBusinessFields()">
                            <div class="p-6 border-2 border-gray-300 rounded-lg peer-checked:border-orange-600 peer-checked:bg-orange-50 transition-all hover:border-orange-400">
                                <div class="text-center">
                                    <div class="text-4xl mb-2">👤</div>
                                    <div class="font-semibold text-lg">Cá nhân</div>
                                    <div class="text-sm text-gray-600 mt-1">Gửi hàng cá nhân, gia đình</div>
                                </div>
                            </div>
                        </label>

                        <label class="cursor-pointer">
                            <input type="radio" name="user_type" value="business" 
                                   class="peer sr-only"
                                   {{ old('user_type') === 'business' ? 'checked' : '' }}
                                   onchange="toggleBusinessFields()">
                            <div class="p-6 border-2 border-gray-300 rounded-lg peer-checked:border-orange-600 peer-checked:bg-orange-50 transition-all hover:border-orange-400">
                                <div class="text-center">
                                    <div class="text-4xl mb-2">🏢</div>
                                    <div class="font-semibold text-lg">Doanh nghiệp</div>
                                    <div class="text-sm text-gray-600 mt-1">Gửi hàng số lượng lớn, có ưu đãi</div>
                                </div>
                            </div>
                        </label>
                    </div>
                    @error('user_type')
                        <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Thông tin chung -->
                <div class="space-y-4 mb-6">
                    <div>
                        <label class="form-label">Họ tên *</label>
                        <input type="text" name="name" value="{{ old('name') }}" required 
                               class="form-input" placeholder="Nguyễn Văn A">
                        @error('name')
                            <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="form-label">Email *</label>
                            <input type="email" name="email" value="{{ old('email') }}" required 
                                   class="form-input" placeholder="example@email.com">
                            @error('email')
                                <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label class="form-label">Số điện thoại *</label>
                            <input type="tel" name="phone" value="{{ old('phone') }}" required 
                                   class="form-input" placeholder="0912345678">
                            @error('phone')
                                <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div>
                        <label class="form-label">Địa chỉ *</label>
                        <textarea name="address" required rows="2" 
                                  class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-orange-500 focus:border-transparent resize-none" 
                                  placeholder="Số nhà, đường, phường, quận, thành phố">{{ old('address') }}</textarea>
                        @error('address')
                            <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Thông tin doanh nghiệp (ẩn mặc định) -->
                <div id="businessFields" class="space-y-4 mb-6 hidden">
                    <div class="bg-blue-50 border-l-4 border-blue-500 p-4 mb-4">
                        <p class="text-sm text-blue-800">
                            <strong>🏢 Thông tin doanh nghiệp:</strong> Vui lòng điền đầy đủ thông tin công ty để được hưởng các chính sách ưu đãi.
                        </p>
                    </div>

                    <div>
                        <label class="form-label">Tên công ty *</label>
                        <input type="text" name="company_name" value="{{ old('company_name') }}" 
                               class="form-input" placeholder="Công ty TNHH ABC">
                        @error('company_name')
                            <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="form-label">Mã số thuế *</label>
                        <input type="text" name="tax_code" value="{{ old('tax_code') }}" 
                               class="form-input" placeholder="0123456789">
                        @error('tax_code')
                            <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="form-label">Địa chỉ công ty *</label>
                        <textarea name="company_address" rows="2" 
                                  class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-orange-500 focus:border-transparent resize-none" 
                                  placeholder="Địa chỉ trụ sở chính">{{ old('company_address') }}</textarea>
                        @error('company_address')
                            <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Mật khẩu -->
                <div class="space-y-4 mb-6">
                    <div>
                        <label class="form-label">Mật khẩu *</label>
                        <input type="password" name="password" required 
                               class="form-input" placeholder="Tối thiểu 8 ký tự">
                        @error('password')
                            <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="form-label">Xác nhận mật khẩu *</label>
                        <input type="password" name="password_confirmation" required 
                               class="form-input" placeholder="Nhập lại mật khẩu">
                    </div>
                </div>

                <!-- Điều khoản -->
                <div class="mb-6">
                    <label class="flex items-start gap-3 cursor-pointer">
                        <input type="checkbox" required class="mt-1">
                        <span class="text-sm text-gray-700">
                            Tôi đồng ý với <a href="#" class="text-orange-600 hover:underline">Điều khoản dịch vụ</a> 
                            và <a href="#" class="text-orange-600 hover:underline">Chính sách bảo mật</a> của FastShip
                        </span>
                    </label>
                </div>

                <!-- Nút đăng ký -->
                <button type="submit" class="btn btn-primary w-full btn-lg">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"/>
                    </svg>
                    Đăng ký tài khoản
                </button>

                <!-- Link đăng nhập -->
                <div class="text-center mt-6">
                    <p class="text-gray-600">
                        Đã có tài khoản? 
                        <a href="{{ route('login') }}" class="text-orange-600 font-semibold hover:underline">Đăng nhập ngay</a>
                    </p>
                </div>
            </form>
        </x-card>
    </div>

    <x-slot:scripts>
        <script>
            function toggleBusinessFields() {
                const userType = document.querySelector('input[name="user_type"]:checked').value;
                const businessFields = document.getElementById('businessFields');
                
                if (userType === 'business') {
                    businessFields.classList.remove('hidden');
                    // Set required cho các field business
                    businessFields.querySelectorAll('input, textarea').forEach(input => {
                        if (input.name) input.required = true;
                    });
                } else {
                    businessFields.classList.add('hidden');
                    // Bỏ required
                    businessFields.querySelectorAll('input, textarea').forEach(input => {
                        if (input.name) input.required = false;
                    });
                }
            }

            // Khởi tạo khi load trang
            document.addEventListener('DOMContentLoaded', toggleBusinessFields);
        </script>
    </x-slot:scripts>
</x-layout>
