<x-layout title="Liên hệ - FastShip">
    <x-page-header 
        title="📧 Liên hệ với chúng tôi" 
        subtitle="Gửi câu hỏi hoặc phản hồi, chúng tôi sẽ trả lời trong 24h"
    />

    <div class="max-w-4xl mx-auto grid grid-cols-1 md:grid-cols-2 gap-8">
        <!-- Form liên hệ -->
        <x-card>
            <x-slot:header>
                <h3 class="text-xl font-bold">Gửi tin nhắn</h3>
            </x-slot:header>

            <form method="POST" action="{{ route('support.contact.submit') }}" class="space-y-4">
                @csrf
                
                <div>
                    <label class="form-label">Họ tên *</label>
                    <input type="text" name="name" required class="form-input" placeholder="Nguyễn Văn A">
                    @error('name')
                        <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="form-label">Email *</label>
                    <input type="email" name="email" required class="form-input" placeholder="example@email.com">
                    @error('email')
                        <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="form-label">Số điện thoại *</label>
                    <input type="tel" name="phone" required class="form-input" placeholder="0912345678">
                    @error('phone')
                        <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="form-label">Chủ đề *</label>
                    <select name="subject" required class="form-select">
                        <option value="">-- Chọn chủ đề --</option>
                        <option value="Tra cứu đơn hàng">Tra cứu đơn hàng</option>
                        <option value="Khiếu nại">Khiếu nại</option>
                        <option value="Góp ý">Góp ý</option>
                        <option value="Hợp tác">Hợp tác kinh doanh</option>
                        <option value="Khác">Khác</option>
                    </select>
                    @error('subject')
                        <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="form-label">Nội dung *</label>
                    <textarea name="message" required rows="5" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-orange-500 focus:border-transparent resize-none" placeholder="Nhập nội dung..."></textarea>
                    @error('message')
                        <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <button type="submit" class="btn btn-primary w-full">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/>
                    </svg>
                    Gửi tin nhắn
                </button>
            </form>
        </x-card>

        <!-- Thông tin liên hệ -->
        <div class="space-y-6">
            <x-card>
                <div class="flex items-start gap-4">
                    <div class="w-12 h-12 bg-orange-100 rounded-lg flex items-center justify-center flex-shrink-0">
                        <svg class="w-6 h-6 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/>
                        </svg>
                    </div>
                    <div>
                        <h4 class="font-semibold text-lg mb-1">Hotline 24/7</h4>
                        <a href="tel:1900xxxx" class="text-2xl font-bold text-orange-600 block">1900-xxxx</a>
                        <p class="text-sm text-gray-500 mt-1">Miễn phí cuộc gọi</p>
                    </div>
                </div>
            </x-card>

            <x-card>
                <div class="flex items-start gap-4">
                    <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center flex-shrink-0">
                        <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                        </svg>
                    </div>
                    <div>
                        <h4 class="font-semibold text-lg mb-1">Email</h4>
                        <a href="mailto:support@fastship.vn" class="text-blue-600 hover:underline">support@fastship.vn</a>
                        <p class="text-sm text-gray-500 mt-1">Phản hồi trong 24h</p>
                    </div>
                </div>
            </x-card>

            <x-card>
                <div class="flex items-start gap-4">
                    <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center flex-shrink-0">
                        <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                        </svg>
                    </div>
                    <div>
                        <h4 class="font-semibold text-lg mb-1">Văn phòng</h4>
                        <p class="text-gray-700">123 Đường ABC</p>
                        <p class="text-gray-700">Quận XYZ, Hà Nội</p>
                        <p class="text-sm text-gray-500 mt-1">T2-T7: 8:00 - 17:30</p>
                    </div>
                </div>
            </x-card>
        </div>
    </div>
</x-layout>
