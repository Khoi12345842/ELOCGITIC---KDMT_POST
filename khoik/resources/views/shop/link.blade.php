<x-layout title="Liên kết Shop - FastShip Business">
    <div class="max-w-4xl mx-auto">
        <x-page-header 
            title="🏪 Liên kết Shop của bạn" 
            subtitle="Kết nối shop với FastShip để đồng bộ đơn hàng tự động"
        />

        <!-- Lợi ích -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-8">
            <x-card class="text-center">
                <div class="text-4xl mb-3">🚀</div>
                <h4 class="font-semibold text-lg mb-2">Tự động đồng bộ</h4>
                <p class="text-sm text-gray-600">Đơn hàng từ shop tự động chuyển sang FastShip</p>
            </x-card>

            <x-card class="text-center">
                <div class="text-4xl mb-3">💰</div>
                <h4 class="font-semibold text-lg mb-2">Ưu đãi đặc biệt</h4>
                <p class="text-sm text-gray-600">Giảm 5-20% phí vận chuyển theo hợp đồng</p>
            </x-card>

            <x-card class="text-center">
                <div class="text-4xl mb-3">📊</div>
                <h4 class="font-semibold text-lg mb-2">Quản lý tập trung</h4>
                <p class="text-sm text-gray-600">Theo dõi tất cả đơn hàng ở một nơi</p>
            </x-card>
        </div>

        <!-- Form liên kết -->
        <x-card>
            <x-slot:header>
                <h3 class="text-xl font-bold">📝 Thông tin liên kết Shop</h3>
            </x-slot:header>

            <form method="POST" action="{{ route('shop.link.store') }}" id="linkShopForm">
                @csrf

                <div class="space-y-6">
                    <!-- Chọn nền tảng -->
                    <div>
                        <label class="form-label">Nền tảng Shop *</label>
                        <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                            @foreach(['Shopee', 'Lazada', 'TikTok Shop', 'Sendo'] as $platform)
                            <label class="cursor-pointer">
                                <input type="radio" name="shop_platform" value="{{ $platform }}" 
                                       class="peer sr-only" 
                                       {{ old('shop_platform') === $platform ? 'checked' : '' }}
                                       required>
                                <div class="p-4 border-2 border-gray-300 rounded-lg peer-checked:border-orange-600 peer-checked:bg-orange-50 transition-all hover:border-orange-400 text-center">
                                    <div class="font-semibold">{{ $platform }}</div>
                                </div>
                            </label>
                            @endforeach
                        </div>
                        @error('shop_platform')
                            <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Thông tin shop -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="form-label">Shop ID *</label>
                            <input type="text" name="shop_id" value="{{ old('shop_id') }}" 
                                   required class="form-input" 
                                   placeholder="Ví dụ: shop123456">
                            <p class="text-xs text-gray-500 mt-1">Tìm trong cài đặt shop của bạn</p>
                            @error('shop_id')
                                <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label class="form-label">Tên Shop *</label>
                            <input type="text" name="shop_name" value="{{ old('shop_name', auth()->user()->company_name) }}" 
                                   required class="form-input" 
                                   placeholder="Tên shop trên sàn">
                            @error('shop_name')
                                <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <!-- Chính sách hợp đồng -->
                    <div class="bg-blue-50 border-l-4 border-blue-500 p-6 rounded-lg">
                        <h4 class="font-bold text-lg mb-4 flex items-center gap-2">
                            <span>📋</span> Chính sách hợp đồng
                        </h4>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="form-label">Mức giảm giá (%) *</label>
                                <select name="discount_rate" required class="form-select">
                                    <option value="">-- Chọn mức giảm --</option>
                                    <option value="5" {{ old('discount_rate') == '5' ? 'selected' : '' }}>5% - Gói Cơ bản</option>
                                    <option value="10" {{ old('discount_rate') == '10' ? 'selected' : '' }}>10% - Gói Tiêu chuẩn</option>
                                    <option value="15" {{ old('discount_rate') == '15' ? 'selected' : '' }}>15% - Gói Cao cấp</option>
                                    <option value="20" {{ old('discount_rate') == '20' ? 'selected' : '' }}>20% - Gói VIP</option>
                                </select>
                                @error('discount_rate')
                                    <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label class="form-label">Thời hạn hợp đồng *</label>
                                <select name="contract_duration" required class="form-select">
                                    <option value="">-- Chọn thời hạn --</option>
                                    <option value="6" {{ old('contract_duration') == '6' ? 'selected' : '' }}>6 tháng</option>
                                    <option value="12" {{ old('contract_duration') == '12' ? 'selected' : '' }}>12 tháng (Khuyến nghị)</option>
                                    <option value="24" {{ old('contract_duration') == '24' ? 'selected' : '' }}>24 tháng</option>
                                    <option value="36" {{ old('contract_duration') == '36' ? 'selected' : '' }}>36 tháng</option>
                                </select>
                                @error('contract_duration')
                                    <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <div class="mt-4 text-sm text-blue-800">
                            <p><strong>Lưu ý:</strong></p>
                            <ul class="list-disc list-inside space-y-1 mt-2">
                                <li>Giảm giá áp dụng cho tất cả đơn hàng từ shop liên kết</li>
                                <li>Hợp đồng có thể gia hạn trước 30 ngày</li>
                                <li>Phí phạt nếu hủy hợp đồng trước hạn: 20% tổng giá trị</li>
                            </ul>
                        </div>
                    </div>

                    <!-- Hợp đồng điện tử -->
                    <div class="border-2 border-gray-300 rounded-lg p-6">
                        <h4 class="font-bold text-lg mb-4 flex items-center gap-2">
                            <span>✍️</span> Hợp đồng điện tử
                        </h4>

                        <div class="bg-gray-50 p-4 rounded-lg max-h-64 overflow-y-auto mb-4 text-sm">
                            <h5 class="font-bold mb-2">HỢP ĐỒNG DỊCH VỤ VẬN CHUYỂN</h5>
                            
                            <p class="mb-2"><strong>Bên A:</strong> Công ty TNHH FastShip Logistics</p>
                            <p class="mb-4"><strong>Bên B:</strong> {{ auth()->user()->company_name }} (MST: {{ auth()->user()->tax_code }})</p>

                            <p class="font-semibold mb-2">ĐIỀU 1: PHẠM VI DỊCH VỤ</p>
                            <p class="mb-4">Bên A cung cấp dịch vụ vận chuyển hàng hóa cho các đơn hàng từ shop trực tuyến của Bên B trên các sàn thương mại điện tử.</p>

                            <p class="font-semibold mb-2">ĐIỀU 2: QUYỀN VÀ NGHĨA VỤ</p>
                            <p class="mb-2"><strong>2.1. Bên A cam kết:</strong></p>
                            <ul class="list-disc list-inside mb-4 space-y-1">
                                <li>Vận chuyển hàng hóa an toàn, đúng hạn</li>
                                <li>Áp dụng mức giảm giá theo thỏa thuận</li>
                                <li>Bồi thường thiệt hại theo quy định</li>
                                <li>Cung cấp hệ thống tra cứu 24/7</li>
                            </ul>

                            <p class="mb-2"><strong>2.2. Bên B cam kết:</strong></p>
                            <ul class="list-disc list-inside mb-4 space-y-1">
                                <li>Cung cấp thông tin chính xác về đơn hàng</li>
                                <li>Đóng gói hàng hóa đúng quy cách</li>
                                <li>Thanh toán phí vận chuyển đầy đủ, đúng hạn</li>
                                <li>Tuân thủ các quy định về hàng hóa vận chuyển</li>
                            </ul>

                            <p class="font-semibold mb-2">ĐIỀU 3: CHÍNH SÁCH GIÁ</p>
                            <p class="mb-4">Bên B được hưởng ưu đãi giảm giá theo gói hợp đồng đã chọn, áp dụng trong suốt thời hạn hợp đồng.</p>

                            <p class="font-semibold mb-2">ĐIỀU 4: THỜI HẠN VÀ GIA HẠN</p>
                            <p class="mb-4">Hợp đồng có hiệu lực theo thời hạn đã chọn, có thể gia hạn theo thỏa thuận của hai bên.</p>

                            <p class="font-semibold mb-2">ĐIỀU 5: ĐIỀU KHOẢN CHẤM DỨT</p>
                            <p class="mb-4">Một trong hai bên có thể chấm dứt hợp đồng trước hạn với thông báo trước 30 ngày. Phí phạt áp dụng theo quy định.</p>

                            <p class="font-semibold mb-2">ĐIỀU 6: ĐIỀU KHOẢN CHUNG</p>
                            <p class="mb-2">Hợp đồng này được ký kết điện tử và có giá trị pháp lý tương đương hợp đồng giấy. Mọi tranh chấp sẽ được giải quyết thông qua thương lượng hoặc theo pháp luật Việt Nam.</p>
                        </div>

                        <!-- Checkbox đồng ý -->
                        <label class="flex items-start gap-3 cursor-pointer mb-4">
                            <input type="checkbox" name="agree_terms" value="1" required class="mt-1">
                            <span class="text-sm text-gray-700">
                                Tôi đã đọc, hiểu rõ và đồng ý với tất cả các điều khoản trong hợp đồng trên. 
                                Tôi cam kết thực hiện đầy đủ các nghĩa vụ được quy định.
                            </span>
                        </label>
                        @error('agree_terms')
                            <p class="text-red-600 text-sm">{{ $message }}</p>
                        @enderror

                        <!-- Chữ ký điện tử -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div class="bg-gray-50 p-4 rounded-lg">
                                <p class="text-sm font-semibold mb-2">Người ký (Bên B):</p>
                                <p class="text-sm">{{ auth()->user()->name }}</p>
                                <p class="text-sm text-gray-600">{{ auth()->user()->company_name }}</p>
                            </div>
                            <div class="bg-gray-50 p-4 rounded-lg">
                                <p class="text-sm font-semibold mb-2">Thời gian ký:</p>
                                <p class="text-sm" id="signTime">{{ now()->format('H:i:s d/m/Y') }}</p>
                                <p class="text-sm text-gray-600">IP: <span id="userIP">Đang tải...</span></p>
                            </div>
                        </div>
                    </div>

                    <!-- Buttons -->
                    <div class="flex gap-4">
                        <button type="submit" class="btn btn-primary btn-lg flex-1">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            Ký hợp đồng & Liên kết Shop
                        </button>
                        <a href="{{ route('orders.index') }}" class="btn btn-outline btn-lg">
                            Hủy
                        </a>
                    </div>
                </div>
            </form>
        </x-card>
    </div>

    <x-slot:scripts>
        <script>
            // Get user IP
            fetch('https://api.ipify.org?format=json')
                .then(response => response.json())
                .then(data => {
                    document.getElementById('userIP').textContent = data.ip;
                })
                .catch(() => {
                    document.getElementById('userIP').textContent = 'Không xác định';
                });

            // Confirm before submit
            document.getElementById('linkShopForm').addEventListener('submit', function(e) {
                if (!confirm('Bạn xác nhận đã đọc và đồng ý với tất cả điều khoản hợp đồng?')) {
                    e.preventDefault();
                }
            });
        </script>
    </x-slot:scripts>
</x-layout>
