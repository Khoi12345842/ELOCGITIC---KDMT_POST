<x-layout title="Tìm kiếm bưu cục - FastShip">
    <div class="max-w-7xl mx-auto">
        <!-- Hero Section -->
        <div class="bg-gradient-to-r from-blue-900 to-blue-800 text-white rounded-2xl p-8 mb-8 shadow-xl">
            <div class="text-center">
                <h1 class="text-4xl font-bold mb-3">📍 Tìm kiếm bưu cục</h1>
                <p class="text-blue-100 text-lg">Tìm bưu cục gần bạn để gửi và nhận hàng tiện lợi nhất</p>
            </div>

            <!-- Form tìm kiếm -->
            <form method="GET" action="{{ route('post-offices.index') }}" class="mt-8" id="searchForm">
                <div class="max-w-4xl mx-auto">
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <div>
                            <label class="block text-blue-100 text-sm mb-2">Tỉnh/Thành phố *</label>
                            <select name="city" id="citySelect" required 
                                    class="w-full px-4 py-3 text-gray-900 rounded-lg focus:outline-none focus:ring-4 focus:ring-blue-300">
                                <option value="">-- Chọn tỉnh/thành --</option>
                                @foreach($cities as $cityName => $districts)
                                    <option value="{{ $cityName }}" {{ $city === $cityName ? 'selected' : '' }}>
                                        {{ $cityName }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div>
                            <label class="block text-blue-100 text-sm mb-2">Quận/Huyện</label>
                            <select name="district" id="districtSelect"
                                    class="w-full px-4 py-3 text-gray-900 rounded-lg focus:outline-none focus:ring-4 focus:ring-blue-300">
                                <option value="">-- Tất cả --</option>
                            </select>
                        </div>

                        <div class="flex items-end">
                            <button type="submit" class="w-full px-6 py-3 bg-orange-600 text-white rounded-lg hover:bg-orange-700 font-bold shadow-lg transition-all hover:shadow-xl">
                                <svg class="w-5 h-5 inline-block mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                                </svg>
                                Tìm kiếm
                            </button>
                        </div>
                    </div>
                </div>
            </form>
        </div>

        @if($postOffices)
        <!-- Kết quả tìm kiếm -->
        <div class="mb-6">
            <div class="flex items-center justify-between mb-4">
                <h2 class="text-2xl font-bold text-gray-900">
                    📌 Kết quả tìm kiếm
                    <span class="text-orange-600">({{ count($postOffices) }} bưu cục)</span>
                </h2>
                @if($city || $district)
                <a href="{{ route('post-offices.index') }}" class="text-sm text-blue-600 hover:underline">
                    🔄 Xóa bộ lọc
                </a>
                @endif
            </div>

            @if(count($postOffices) > 0)
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach($postOffices as $office)
                <x-card class="hover:shadow-xl transition-all hover:-translate-y-1">
                    <div class="space-y-4">
                        <!-- Header -->
                        <div class="flex items-start justify-between">
                            <div class="flex-1">
                                <h3 class="text-lg font-bold text-gray-900">{{ $office['name'] }}</h3>
                                <p class="text-sm text-gray-600 mt-1">
                                    📍 {{ $office['district'] }}, {{ $office['city'] }}
                                </p>
                            </div>
                            <div class="w-12 h-12 bg-orange-100 rounded-full flex items-center justify-center flex-shrink-0">
                                <span class="text-2xl">📮</span>
                            </div>
                        </div>

                        <hr class="border-gray-200">

                        <!-- Info -->
                        <div class="space-y-2 text-sm">
                            <div class="flex items-start gap-2">
                                <svg class="w-5 h-5 text-gray-500 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                                </svg>
                                <span class="text-gray-700">{{ $office['address'] }}</span>
                            </div>

                            <div class="flex items-center gap-2">
                                <svg class="w-5 h-5 text-gray-500 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/>
                                </svg>
                                <a href="tel:{{ $office['phone'] }}" class="text-blue-600 font-semibold hover:underline">
                                    {{ $office['phone'] }}
                                </a>
                            </div>

                            <div class="flex items-center gap-2">
                                <svg class="w-5 h-5 text-gray-500 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                                <span class="text-gray-700">{{ $office['hours'] }}</span>
                            </div>
                        </div>

                        <hr class="border-gray-200">

                        <!-- Services -->
                        <div>
                            <p class="text-xs font-semibold text-gray-600 mb-2">Dịch vụ:</p>
                            <div class="flex flex-wrap gap-2">
                                @foreach($office['services'] as $service)
                                <span class="px-2 py-1 bg-blue-50 text-blue-700 text-xs rounded-full font-medium">
                                    {{ $service }}
                                </span>
                                @endforeach
                            </div>
                        </div>

                        <!-- Actions -->
                        <div class="grid grid-cols-2 gap-2 pt-2">
                            <a href="https://www.google.com/maps/search/{{ urlencode($office['name'] . ' ' . $office['address']) }}" 
                               target="_blank"
                               class="btn btn-sm btn-outline text-xs">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13l6-3m-6 3V7m6 10l4.553 2.276A1 1 0 0021 18.382V7.618a1 1 0 00-.553-.894L15 4m0 13V4m0 0L9 7"/>
                                </svg>
                                Bản đồ
                            </a>
                            <a href="tel:{{ $office['phone'] }}" class="btn btn-sm btn-primary text-xs">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/>
                                </svg>
                                Gọi ngay
                            </a>
                        </div>
                    </div>
                </x-card>
                @endforeach
            </div>
            @else
            <x-card class="text-center py-12">
                <div class="text-6xl mb-4">😞</div>
                <h3 class="text-2xl font-bold text-gray-900 mb-2">Không tìm thấy bưu cục</h3>
                <p class="text-gray-600 mb-6">Không có bưu cục nào tại {{ $district ? $district . ', ' : '' }}{{ $city }}</p>
                <a href="{{ route('post-offices.index') }}" class="btn btn-primary">
                    Xem tất cả bưu cục
                </a>
            </x-card>
            @endif
        </div>
        @endif

        <!-- Thông tin thêm -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mt-8">
            <x-card class="bg-gradient-to-br from-orange-50 to-orange-100 border-orange-200">
                <div class="text-center">
                    <div class="text-4xl mb-3">🕐</div>
                    <h4 class="font-bold text-lg mb-2">Giờ làm việc</h4>
                    <p class="text-sm text-gray-700">Hầu hết các bưu cục mở cửa từ 7:00 - 21:00 hàng ngày</p>
                </div>
            </x-card>

            <x-card class="bg-gradient-to-br from-blue-50 to-blue-100 border-blue-200">
                <div class="text-center">
                    <div class="text-4xl mb-3">📦</div>
                    <h4 class="font-bold text-lg mb-2">Đa dịch vụ</h4>
                    <p class="text-sm text-gray-700">Gửi hàng, nhận hàng, EMS, chuyển phát nhanh, thu hộ COD</p>
                </div>
            </x-card>

            <x-card class="bg-gradient-to-br from-green-50 to-green-100 border-green-200">
                <div class="text-center">
                    <div class="text-4xl mb-3">💰</div>
                    <h4 class="font-bold text-lg mb-2">Giá cả hợp lý</h4>
                    <p class="text-sm text-gray-700">Giá cước cạnh tranh, nhiều ưu đãi cho khách hàng thân thiết</p>
                </div>
            </x-card>
        </div>
    </div>

    <x-slot:scripts>
        <script>
            // District data
            const citiesData = @json($cities);
            
            const citySelect = document.getElementById('citySelect');
            const districtSelect = document.getElementById('districtSelect');
            const selectedDistrict = "{{ $district }}";

            // Update districts when city changes
            citySelect.addEventListener('change', function() {
                const selectedCity = this.value;
                districtSelect.innerHTML = '<option value="">-- Tất cả --</option>';
                
                if (selectedCity && citiesData[selectedCity]) {
                    citiesData[selectedCity].forEach(district => {
                        const option = document.createElement('option');
                        option.value = district;
                        option.textContent = district;
                        districtSelect.appendChild(option);
                    });
                }
            });

            // Initialize districts on page load
            if (citySelect.value) {
                const event = new Event('change');
                citySelect.dispatchEvent(event);
                
                // Set selected district
                setTimeout(() => {
                    if (selectedDistrict) {
                        districtSelect.value = selectedDistrict;
                    }
                }, 100);
            }
        </script>
    </x-slot:scripts>
</x-layout>
