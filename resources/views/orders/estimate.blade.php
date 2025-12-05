<x-layout title="Dự đoán chi phí vận chuyển">
    <div class="max-w-2xl mx-auto">
        <x-card>
            <x-slot:header>
                <h1 class="text-3xl font-bold text-center">Dự Đoán Chi Phí Vận Chuyển</h1>
            </x-slot:header>
            
            {{-- Form nhập thông tin --}}
            <form method="GET" action="{{ route('orders.estimate') }}" class="space-y-4">
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="form-label">Tỉnh/TP gửi</label>
                        <select name="from_province" required class="form-select">
                            <option value="">-- Chọn --</option>
                            <option value="Hà Nội" {{ request('from_province') == 'Hà Nội' ? 'selected' : '' }}>Hà Nội</option>
                            <option value="TP.HCM" {{ request('from_province') == 'TP.HCM' ? 'selected' : '' }}>TP.HCM</option>
                            <option value="Đà Nẵng" {{ request('from_province') == 'Đà Nẵng' ? 'selected' : '' }}>Đà Nẵng</option>
                            <option value="Hải Phòng" {{ request('from_province') == 'Hải Phòng' ? 'selected' : '' }}>Hải Phòng</option>
                            <option value="Cần Thơ" {{ request('from_province') == 'Cần Thơ' ? 'selected' : '' }}>Cần Thơ</option>
                        </select>
                    </div>
                    
                    <div>
                        <label class="form-label">Tỉnh/TP nhận</label>
                        <select name="to_province" required class="form-select">
                            <option value="">-- Chọn --</option>
                            <option value="Hà Nội" {{ request('to_province') == 'Hà Nội' ? 'selected' : '' }}>Hà Nội</option>
                            <option value="TP.HCM" {{ request('to_province') == 'TP.HCM' ? 'selected' : '' }}>TP.HCM</option>
                            <option value="Đà Nẵng" {{ request('to_province') == 'Đà Nẵng' ? 'selected' : '' }}>Đà Nẵng</option>
                            <option value="Hải Phòng" {{ request('to_province') == 'Hải Phòng' ? 'selected' : '' }}>Hải Phòng</option>
                            <option value="Cần Thơ" {{ request('to_province') == 'Cần Thơ' ? 'selected' : '' }}>Cần Thơ</option>
                        </select>
                    </div>
                </div>
                
                <div>
                    <label class="form-label">Cân nặng (kg)</label>
                    <input type="number" name="weight" step="0.1" min="0.1" max="1000" 
                           value="{{ request('weight') }}" required
                           class="form-input" placeholder="Nhập cân nặng">
                </div>
                
                <div class="flex items-center">
                    <input type="checkbox" name="is_express" id="is_express" value="1"
                           {{ request('is_express') ? 'checked' : '' }}
                           class="w-4 h-4 text-orange-600 border-gray-300 rounded focus:ring-orange-500">
                    <label for="is_express" class="ml-2 text-sm font-medium text-gray-700">
                        Giao hàng hỏa tốc (+30.000đ)
                    </label>
                </div>
                
                <button type="submit" class="btn btn-primary w-full">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z"/>
                    </svg>
                    Tính chi phí
                </button>
            </form>
            
            {{-- Kết quả --}}
            @if(isset($totalPrice))
                <div class="mt-8 p-6 bg-gradient-to-r from-green-50 to-blue-50 border-2 border-green-500 rounded-lg">
                    <h2 class="text-2xl font-bold text-green-700 mb-4 flex items-center gap-2">
                        <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        Kết quả dự đoán
                    </h2>
                    
                    <div class="space-y-2 text-lg">
                        <div class="flex justify-between">
                            <span class="text-gray-700">Điểm gửi:</span>
                            <span class="font-semibold">{{ $validated['from_province'] }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-700">Điểm nhận:</span>
                            <span class="font-semibold">{{ $validated['to_province'] }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-700">Cân nặng:</span>
                            <span class="font-semibold">{{ $validated['weight'] }} kg</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-700">Thời gian dự kiến:</span>
                            <span class="font-semibold">{{ $estimatedDays }} ngày</span>
                        </div>
                        
                        <hr class="my-4 border-green-200">
                        
                        <div class="flex justify-between items-center bg-white rounded-lg p-4 shadow-md">
                            <span class="text-xl font-bold text-gray-700">Tổng chi phí:</span>
                            <span class="text-3xl font-bold text-green-600">
                                {{ number_format($totalPrice) }}đ
                            </span>
                        </div>
                    </div>
                    
                    {{-- Call to action --}}
                    <div class="mt-6 flex gap-3">
                        @guest
                            <button onclick="alert('Vui lòng đăng nhập để tạo đơn!')" 
                                   class="flex-1 bg-gray-400 text-white py-3 rounded-lg text-center cursor-not-allowed font-semibold">
                                Đăng nhập để tạo đơn
                            </button>
                            <a href="#" class="flex-1 btn btn-primary">
                                Đăng ký ngay
                            </a>
                        @else
                            <a href="#" class="w-full btn btn-primary btn-lg">
                                Tạo đơn với giá này
                            </a>
                        @endguest
                    </div>
                </div>
            @endif
            
            <x-slot:footer>
                <div class="text-center text-sm text-gray-600">
                    <a href="{{ route('orders.track') }}" class="text-orange-600 hover:underline">
                        Tra cứu đơn hàng
                    </a>
                    <span class="mx-2">•</span>
                    <a href="{{ route('orders.index') }}" class="text-orange-600 hover:underline">
                        Danh sách đơn hàng
                    </a>
                </div>
            </x-slot:footer>
        </x-card>
    </div>
</x-layout>
