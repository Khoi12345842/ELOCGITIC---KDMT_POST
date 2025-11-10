<x-layout title="Cài đặt - FastShip">
    <div class="max-w-4xl mx-auto">
        <x-page-header 
            title="Cài đặt tài khoản" 
            subtitle="Quản lý thông tin cá nhân và bảo mật"
        />

        <!-- Tabs -->
        <div class="mb-6">
            <div class="border-b border-gray-200">
                <nav class="-mb-px flex gap-6">
                    <button onclick="showSettingsTab('profile')" id="tab-profile" 
                            class="settings-tab-button border-b-2 border-orange-600 text-orange-600 py-3 px-2 font-semibold">
                        Thông tin cá nhân
                    </button>
                    <button onclick="showSettingsTab('password')" id="tab-password" 
                            class="settings-tab-button border-b-2 border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 py-3 px-2 font-semibold">
                        Đổi mật khẩu
                    </button>
                    <button onclick="showSettingsTab('notifications')" id="tab-notifications" 
                            class="settings-tab-button border-b-2 border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 py-3 px-2 font-semibold">
                        Thông báo
                    </button>
                    @if(auth()->user()->isBusiness())
                    <button onclick="showSettingsTab('business')" id="tab-business" 
                            class="settings-tab-button border-b-2 border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 py-3 px-2 font-semibold">
                        Thông tin doanh nghiệp
                    </button>
                    @endif
                </nav>
            </div>
        </div>

        <!-- Tab: Thông tin cá nhân -->
        <div id="content-profile" class="settings-tab-content">
            <x-card>
                <x-slot:header>
                    <h3 class="text-lg font-bold">Thông tin cá nhân</h3>
                </x-slot:header>

                <form method="POST" action="{{ route('settings.profile.update') }}">
                    @csrf
                    @method('PUT')

                    <div class="space-y-4">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="form-label">Họ và tên *</label>
                                <input type="text" name="name" value="{{ old('name', auth()->user()->name) }}" 
                                       required class="form-input">
                                @error('name')
                                    <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label class="form-label">Email</label>
                                <input type="email" value="{{ auth()->user()->email }}" 
                                       disabled class="form-input bg-gray-100 cursor-not-allowed">
                                <p class="text-xs text-gray-500 mt-1">Email không thể thay đổi</p>
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="form-label">Số điện thoại *</label>
                                <input type="text" name="phone" value="{{ old('phone', auth()->user()->phone) }}" 
                                       required class="form-input">
                                @error('phone')
                                    <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label class="form-label">Loại tài khoản</label>
                    <input type="text" value="{{ auth()->user()->isBusiness() ? 'Doanh nghiệp' : 'Cá nhân' }}" 
                                       disabled class="form-input bg-gray-100 cursor-not-allowed">
                            </div>
                        </div>

                        <div>
                            <label class="form-label">Địa chỉ</label>
                            <textarea name="address" rows="3" class="form-input">{{ old('address', auth()->user()->address) }}</textarea>
                            @error('address')
                                <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        @if(auth()->user()->isBusiness())
                        <div class="border-t pt-4 mt-4">
                            <h4 class="font-semibold mb-4">Thông tin doanh nghiệp</h4>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <label class="form-label">Tên công ty *</label>
                                    <input type="text" name="company_name" value="{{ old('company_name', auth()->user()->company_name) }}" 
                                           required class="form-input">
                                    @error('company_name')
                                        <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div>
                                    <label class="form-label">Mã số thuế *</label>
                                    <input type="text" name="tax_code" value="{{ old('tax_code', auth()->user()->tax_code) }}" 
                                           required class="form-input">
                                    @error('tax_code')
                                        <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        @endif
                    </div>

                    <div class="flex gap-4 mt-6">
                        <button type="submit" class="btn btn-primary">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                            </svg>
                            Lưu thay đổi
                        </button>
                        <a href="{{ route('orders.index') }}" class="btn btn-outline">Hủy</a>
                    </div>
                </form>
            </x-card>
        </div>

        <!-- Tab: Đổi mật khẩu -->
        <div id="content-password" class="settings-tab-content hidden">
            <x-card>
                <x-slot:header>
                    <h3 class="text-lg font-bold">Đổi mật khẩu</h3>
                </x-slot:header>

                <form method="POST" action="{{ route('settings.password.update') }}">
                    @csrf
                    @method('PUT')

                    <div class="space-y-4">
                        <div class="bg-yellow-50 border-l-4 border-yellow-400 p-4">
                            <p class="text-sm text-yellow-800">
                                <strong>Lưu ý:</strong> Mật khẩu mới phải có ít nhất 8 ký tự và bao gồm chữ hoa, chữ thường, số.
                            </p>
                        </div>

                        <div>
                            <label class="form-label">Mật khẩu hiện tại *</label>
                            <input type="password" name="current_password" required class="form-input" autocomplete="current-password">
                            @error('current_password')
                                <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label class="form-label">Mật khẩu mới *</label>
                            <input type="password" name="password" required class="form-input" autocomplete="new-password">
                            @error('password')
                                <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label class="form-label">Xác nhận mật khẩu mới *</label>
                            <input type="password" name="password_confirmation" required class="form-input" autocomplete="new-password">
                        </div>
                    </div>

                    <div class="flex gap-4 mt-6">
                        <button type="submit" class="btn btn-primary">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                            </svg>
                            Đổi mật khẩu
                        </button>
                        <button type="reset" class="btn btn-outline">Xóa form</button>
                    </div>
                </form>
            </x-card>
        </div>

        <!-- Tab: Thông báo -->
        <div id="content-notifications" class="settings-tab-content hidden">
            <x-card>
                <x-slot:header>
                    <h3 class="text-lg font-bold">Cài đặt thông báo</h3>
                </x-slot:header>

                <form method="POST" action="{{ route('settings.notifications.update') }}">
                    @csrf
                    @method('PUT')

                    <div class="space-y-6">
                        <div class="flex items-start gap-4">
                            <input type="checkbox" name="email_notifications" value="1" 
                                   {{ session('email_notifications', true) ? 'checked' : '' }}
                                   class="mt-1 rounded">
                            <div class="flex-1">
                                <label class="font-semibold text-gray-900 cursor-pointer">
                                    Thông báo qua Email
                                </label>
                                <p class="text-sm text-gray-600 mt-1">
                                    Nhận email khi đơn hàng thay đổi trạng thái, chương trình khuyến mãi mới
                                </p>
                            </div>
                        </div>

                        <div class="flex items-start gap-4">
                            <input type="checkbox" name="sms_notifications" value="1" 
                                   {{ session('sms_notifications', false) ? 'checked' : '' }}
                                   class="mt-1 rounded">
                            <div class="flex-1">
                                <label class="font-semibold text-gray-900 cursor-pointer">
                                    Thông báo qua SMS
                                </label>
                                <p class="text-sm text-gray-600 mt-1">
                                    Nhận tin nhắn SMS khi đơn hàng được giao thành công
                                </p>
                            </div>
                        </div>

                        <div class="bg-blue-50 border-l-4 border-blue-500 p-4">
                            <p class="text-sm text-blue-800">
                                <strong>Thông tin:</strong> Thông báo email luôn được bật mặc định để đảm bảo bạn không bỏ lỡ các thông tin quan trọng về đơn hàng.
                            </p>
                        </div>
                    </div>

                    <div class="flex gap-4 mt-6">
                        <button type="submit" class="btn btn-primary">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>
                            </svg>
                            Lưu cài đặt
                        </button>
                    </div>
                </form>
            </x-card>
        </div>

        <!-- Tab: Thông tin doanh nghiệp (chỉ business) -->
        @if(auth()->user()->isBusiness())
        <div id="content-business" class="settings-tab-content hidden">
            <x-card>
                <x-slot:header>
                    <h3 class="text-lg font-bold">Thông tin doanh nghiệp</h3>
                </x-slot:header>

                <div class="space-y-6">
                    <!-- Thông tin shop -->
                    <div>
                        <h4 class="font-semibold mb-4">Shop liên kết</h4>
                        @if(auth()->user()->shop_id)
                            <div class="bg-green-50 border border-green-200 rounded-lg p-4">
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm">
                                    <div>
                                        <span class="text-gray-600">Nền tảng:</span>
                                        <span class="font-semibold ml-2">{{ auth()->user()->shop_platform }}</span>
                                    </div>
                                    <div>
                                        <span class="text-gray-600">Shop ID:</span>
                                        <span class="font-semibold ml-2">{{ auth()->user()->shop_id }}</span>
                                    </div>
                                    <div>
                                        <span class="text-gray-600">Tên shop:</span>
                                        <span class="font-semibold ml-2">{{ auth()->user()->shop_name }}</span>
                                    </div>
                                    <div>
                                        <span class="text-gray-600">Giảm giá:</span>
                                        <span class="font-semibold ml-2 text-green-600">{{ auth()->user()->discount_rate }}%</span>
                                    </div>
                                </div>
                                <div class="mt-4 pt-4 border-t border-green-200">
                                    <a href="{{ route('shop.dashboard') }}" class="btn btn-sm btn-primary">
                                        Quản lý Shop
                                    </a>
                                </div>
                            </div>
                        @else
                            <div class="bg-gray-50 border border-gray-200 rounded-lg p-6 text-center">
                                <p class="text-gray-600 mb-4">Chưa liên kết shop</p>
                                <a href="{{ route('shop.link') }}" class="btn btn-primary">
                                    Liên kết Shop ngay
                                </a>
                            </div>
                        @endif
                    </div>

                    <!-- Hợp đồng -->
                    <div>
                        <h4 class="font-semibold mb-4">Hợp đồng</h4>
                        @if(auth()->user()->has_contract)
                            <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                                <div class="grid grid-cols-1 md:grid-cols-3 gap-4 text-sm">
                                    <div>
                                        <span class="text-gray-600">Ngày ký:</span>
                                        <span class="font-semibold ml-2">{{ \Carbon\Carbon::parse(auth()->user()->contract_start_date)->format('d/m/Y') }}</span>
                                    </div>
                                    <div>
                                        <span class="text-gray-600">Ngày hết hạn:</span>
                                        <span class="font-semibold ml-2">{{ \Carbon\Carbon::parse(auth()->user()->contract_end_date)->format('d/m/Y') }}</span>
                                    </div>
                                    <div>
                                        <span class="text-gray-600">Còn lại:</span>
                                        <span class="font-semibold ml-2 text-orange-600">
                                            {{ \Carbon\Carbon::parse(auth()->user()->contract_end_date)->diffInDays(now()) }} ngày
                                        </span>
                                    </div>
                                </div>
                            </div>
                        @else
                            <div class="bg-gray-50 border border-gray-200 rounded-lg p-4 text-center">
                                <p class="text-gray-600">Chưa có hợp đồng</p>
                            </div>
                        @endif
                    </div>

                    <!-- Thống kê -->
                    <div>
                        <h4 class="font-semibold mb-4">Thống kê</h4>
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                            <div class="bg-orange-50 border border-orange-200 rounded-lg p-4 text-center">
                                <div class="text-2xl font-bold text-orange-600">
                                    {{ auth()->user()->orders()->count() }}
                                </div>
                                <div class="text-sm text-gray-600 mt-1">Tổng đơn hàng</div>
                            </div>
                            <div class="bg-blue-50 border border-blue-200 rounded-lg p-4 text-center">
                                <div class="text-2xl font-bold text-blue-600">
                                    {{ auth()->user()->orders()->where('order_type', 'shop_sync')->count() }}
                                </div>
                                <div class="text-sm text-gray-600 mt-1">Đơn từ shop</div>
                            </div>
                            <div class="bg-green-50 border border-green-200 rounded-lg p-4 text-center">
                                <div class="text-2xl font-bold text-green-600">
                                    {{ auth()->user()->discount_rate }}%
                                </div>
                                <div class="text-sm text-gray-600 mt-1">Mức giảm giá</div>
                            </div>
                        </div>
                    </div>
                </div>
            </x-card>
        </div>
        @endif
    </div>

    <x-slot:scripts>
        <script>
            function showSettingsTab(tabName) {
                // Hide all tab contents
                document.querySelectorAll('.settings-tab-content').forEach(content => {
                    content.classList.add('hidden');
                });

                // Remove active state from all buttons
                document.querySelectorAll('.settings-tab-button').forEach(button => {
                    button.classList.remove('border-orange-600', 'text-orange-600');
                    button.classList.add('border-transparent', 'text-gray-500');
                });

                // Show selected tab
                document.getElementById('content-' + tabName).classList.remove('hidden');

                // Activate selected button
                const activeButton = document.getElementById('tab-' + tabName);
                activeButton.classList.remove('border-transparent', 'text-gray-500');
                activeButton.classList.add('border-orange-600', 'text-orange-600');
            }
        </script>
    </x-slot:scripts>
</x-layout>
