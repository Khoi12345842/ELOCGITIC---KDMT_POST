<x-layout title="Tạo đơn hàng - FastShip">
    <div class="max-w-4xl mx-auto">
        <x-page-header 
            title="📦 Tạo đơn hàng mới" 
            subtitle="Điền thông tin để tạo đơn gửi hàng"
        />

        <form method="POST" action="{{ route('orders.create.individual.store') }}">
            @csrf

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                <!-- Thông tin người gửi -->
                <x-card>
                    <x-slot:header>
                        <h3 class="text-lg font-bold flex items-center gap-2">
                            <span>📤</span> Thông tin người gửi
                        </h3>
                    </x-slot:header>

                    <div class="space-y-4">
                        <div>
                            <label class="form-label">Họ tên *</label>
                            <input type="text" name="sender_name" value="{{ old('sender_name', auth()->user()->name) }}" 
                                   required class="form-input" placeholder="Nguyễn Văn A">
                            @error('sender_name')
                                <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label class="form-label">Số điện thoại *</label>
                            <input type="tel" name="sender_phone" value="{{ old('sender_phone', auth()->user()->phone) }}" 
                                   required class="form-input" placeholder="0912345678">
                            @error('sender_phone')
                                <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label class="form-label">Địa chỉ *</label>
                            <textarea name="sender_address" required rows="3" 
                                      class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-orange-500 resize-none" 
                                      placeholder="Số nhà, đường, phường/xã">{{ old('sender_address', auth()->user()->address) }}</textarea>
                            @error('sender_address')
                                <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label class="form-label">Thành phố *</label>
                            <select name="sender_city" required class="form-select">
                                <option value="">-- Chọn thành phố --</option>
                                <option value="Hà Nội" {{ old('sender_city') == 'Hà Nội' ? 'selected' : '' }}>Hà Nội</option>
                                <option value="TP.HCM" {{ old('sender_city') == 'TP.HCM' ? 'selected' : '' }}>TP. Hồ Chí Minh</option>
                                <option value="Đà Nẵng" {{ old('sender_city') == 'Đà Nẵng' ? 'selected' : '' }}>Đà Nẵng</option>
                                <option value="Hải Phòng" {{ old('sender_city') == 'Hải Phòng' ? 'selected' : '' }}>Hải Phòng</option>
                                <option value="Cần Thơ" {{ old('sender_city') == 'Cần Thơ' ? 'selected' : '' }}>Cần Thơ</option>
                            </select>
                            @error('sender_city')
                                <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </x-card>

                <!-- Thông tin người nhận -->
                <x-card>
                    <x-slot:header>
                        <h3 class="text-lg font-bold flex items-center gap-2">
                            <span>📥</span> Thông tin người nhận
                        </h3>
                    </x-slot:header>

                    <div class="space-y-4">
                        <div>
                            <label class="form-label">Họ tên *</label>
                            <input type="text" name="receiver_name" value="{{ old('receiver_name') }}" 
                                   required class="form-input" placeholder="Trần Thị B">
                            @error('receiver_name')
                                <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label class="form-label">Số điện thoại *</label>
                            <input type="tel" name="receiver_phone" value="{{ old('receiver_phone') }}" 
                                   required class="form-input" placeholder="0987654321">
                            @error('receiver_phone')
                                <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label class="form-label">Địa chỉ *</label>
                            <textarea name="receiver_address" required rows="3" 
                                      class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-orange-500 resize-none" 
                                      placeholder="Số nhà, đường, phường/xã">{{ old('receiver_address') }}</textarea>
                            @error('receiver_address')
                                <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label class="form-label">Thành phố *</label>
                            <select name="receiver_city" required class="form-select">
                                <option value="">-- Chọn thành phố --</option>
                                <option value="Hà Nội" {{ old('receiver_city') == 'Hà Nội' ? 'selected' : '' }}>Hà Nội</option>
                                <option value="TP.HCM" {{ old('receiver_city') == 'TP.HCM' ? 'selected' : '' }}>TP. Hồ Chí Minh</option>
                                <option value="Đà Nẵng" {{ old('receiver_city') == 'Đà Nẵng' ? 'selected' : '' }}>Đà Nẵng</option>
                                <option value="Hải Phòng" {{ old('receiver_city') == 'Hải Phòng' ? 'selected' : '' }}>Hải Phòng</option>
                                <option value="Cần Thơ" {{ old('receiver_city') == 'Cần Thơ' ? 'selected' : '' }}>Cần Thơ</option>
                            </select>
                            @error('receiver_city')
                                <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </x-card>
            </div>

            <!-- Thông tin hàng hóa -->
            <x-card class="mb-6">
                <x-slot:header>
                    <h3 class="text-lg font-bold flex items-center gap-2">
                        <span>📋</span> Thông tin hàng hóa
                    </h3>
                </x-slot:header>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div class="md:col-span-2">
                        <label class="form-label">Mô tả hàng hóa</label>
                        <input type="text" name="package_description" value="{{ old('package_description') }}" 
                               class="form-input" placeholder="Ví dụ: Quần áo, Sách, Điện tử...">
                        @error('package_description')
                            <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="form-label">Khối lượng (kg) *</label>
                        <input type="number" name="weight" value="{{ old('weight', '1.0') }}" 
                               step="0.1" min="0.1" max="50" required 
                               class="form-input" placeholder="1.0">
                        @error('weight')
                            <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="form-label">Tiền thu hộ (COD)</label>
                        <input type="number" name="cod_amount" value="{{ old('cod_amount', '0') }}" 
                               step="1000" min="0" 
                               class="form-input" placeholder="0">
                        @error('cod_amount')
                            <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="md:col-span-2">
                        <label class="form-label">Ghi chú</label>
                        <textarea name="notes" rows="2" 
                                  class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-orange-500 resize-none" 
                                  placeholder="Ghi chú thêm cho đơn hàng...">{{ old('notes') }}</textarea>
                        @error('notes')
                            <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </x-card>

            <!-- Buttons -->
            <div class="flex gap-4">
                <button type="submit" class="btn btn-primary btn-lg flex-1">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                    </svg>
                    Tạo đơn hàng
                </button>
                <a href="{{ route('orders.index') }}" class="btn btn-outline btn-lg">
                    Hủy
                </a>
            </div>
        </form>
    </div>
</x-layout>
