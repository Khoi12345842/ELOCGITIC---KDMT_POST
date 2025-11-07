<x-layout title="Lên đơn theo lô - FastShip Business">
    <div class="max-w-6xl mx-auto">
        <x-page-header 
            title="📊 Lên đơn theo lô" 
            subtitle="Dành cho doanh nghiệp - Upload file Excel hoặc nhập thủ công nhiều đơn"
        />

        <!-- Thông tin ưu đãi -->
        <div class="bg-gradient-to-r from-blue-50 to-blue-100 border-l-4 border-blue-500 p-6 rounded-lg mb-6">
            <div class="flex items-start gap-4">
                <div class="text-4xl">🏢</div>
                <div class="flex-1">
                    <h3 class="font-bold text-lg text-gray-900 mb-2">Ưu đãi doanh nghiệp của bạn</h3>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 text-sm">
                        <div class="flex items-center gap-2">
                            <span class="text-2xl">💰</span>
                            <div>
                                <div class="font-semibold">Giảm giá vận chuyển</div>
                                <div class="text-blue-700">{{ auth()->user()->discount_rate }}%</div>
                            </div>
                        </div>
                        <div class="flex items-center gap-2">
                            <span class="text-2xl">🏪</span>
                            <div>
                                <div class="font-semibold">Shop liên kết</div>
                                <div class="text-blue-700">{{ auth()->user()->shop_name ?? 'Chưa liên kết' }}</div>
                            </div>
                        </div>
                        <div class="flex items-center gap-2">
                            <span class="text-2xl">📄</span>
                            <div>
                                <div class="font-semibold">Hợp đồng</div>
                                <div class="text-blue-700">{{ auth()->user()->has_contract ? 'Đang hiệu lực' : 'Chưa có' }}</div>
                            </div>
                        </div>
                    </div>

                    <!-- Nút liên kết shop nếu chưa liên kết -->
                    @if(!auth()->user()->has_contract || !auth()->user()->shop_id)
                        <div class="mt-4 pt-4 border-t border-blue-200">
                            <p class="text-sm text-gray-700 mb-3">
                                💡 <strong>Liên kết shop của bạn</strong> để tự động đồng bộ đơn hàng và hưởng thêm ưu đãi!
                            </p>
                            <a href="{{ route('shop.link') }}" class="btn btn-primary btn-sm inline-flex items-center gap-2">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1"/>
                                </svg>
                                Liên kết Shop ngay
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Đơn hàng từ Shop (nếu có) -->
        @if($shopOrderCount > 0)
        <div class="mt-8">
            <x-card>
                <x-slot:header>
                    <div class="flex items-center justify-between">
                        <div class="flex items-center gap-3">
                            <div class="bg-gradient-to-br from-green-500 to-teal-600 text-white p-3 rounded-xl">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/>
                                </svg>
                            </div>
                            <div>
                                <h3 class="text-lg font-bold">🏪 Đơn hàng từ Shop của bạn</h3>
                                <p class="text-sm text-gray-600">Đã đồng bộ {{ $shopOrderCount }} đơn hàng từ shop liên kết</p>
                            </div>
                        </div>
                        <a href="{{ route('shop.dashboard') }}" class="btn btn-secondary">
                            Xem tất cả →
                        </a>
                    </div>
                </x-slot:header>

                <!-- 10 đơn gần nhất -->
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Mã đơn</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Người nhận</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Địa chỉ</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Trạng thái</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Ngày tạo</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Thao tác</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach($shopOrders as $order)
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="text-sm font-medium text-gray-900">{{ $order->tracking_number }}</span>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="text-sm">
                                        <div class="font-medium text-gray-900">{{ $order->receiver_name }}</div>
                                        <div class="text-gray-500">{{ $order->receiver_phone }}</div>
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="text-sm text-gray-900">{{ $order->receiver_city }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @php
                                        $statusConfig = [
                                            'pending' => ['bg' => 'bg-yellow-100', 'text' => 'text-yellow-800', 'label' => '⏳ Chờ lấy hàng'],
                                            'picked_up' => ['bg' => 'bg-blue-100', 'text' => 'text-blue-800', 'label' => '📦 Đã lấy hàng'],
                                            'in_transit' => ['bg' => 'bg-purple-100', 'text' => 'text-purple-800', 'label' => '🚚 Đang vận chuyển'],
                                            'out_for_delivery' => ['bg' => 'bg-indigo-100', 'text' => 'text-indigo-800', 'label' => '🏃 Đang giao'],
                                            'delivered' => ['bg' => 'bg-green-100', 'text' => 'text-green-800', 'label' => '✅ Đã giao'],
                                            'failed' => ['bg' => 'bg-red-100', 'text' => 'text-red-800', 'label' => '❌ Giao thất bại'],
                                            'returned' => ['bg' => 'bg-gray-100', 'text' => 'text-gray-800', 'label' => '↩️ Hoàn trả'],
                                        ];
                                        $config = $statusConfig[$order->status] ?? ['bg' => 'bg-gray-100', 'text' => 'text-gray-800', 'label' => $order->status];
                                    @endphp
                                    <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full {{ $config['bg'] }} {{ $config['text'] }}">
                                        {{ $config['label'] }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    {{ $order->created_at->format('d/m/Y H:i') }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm">
                                    <a href="{{ route('orders.track') }}?tracking_number={{ $order->tracking_number }}" 
                                       class="text-orange-600 hover:text-orange-800 font-medium">
                                        Chi tiết →
                                    </a>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                @if($shopOrderCount > 10)
                <div class="mt-4 text-center text-sm text-gray-600 bg-gray-50 py-3 rounded-lg">
                    Hiển thị 10 đơn gần nhất. 
                    <a href="{{ route('shop.dashboard') }}" class="text-orange-600 hover:text-orange-800 font-semibold">
                        Xem {{ $shopOrderCount - 10 }} đơn còn lại →
                    </a>
                </div>
                @endif
            </x-card>
        </div>
        @endif

        <!-- Tabs -->
        <div class="mb-6">
            <div class="border-b border-gray-200">
                <nav class="-mb-px flex gap-4">
                    <button onclick="showTab('upload')" id="tab-upload" 
                            class="tab-button border-b-2 border-orange-600 text-orange-600 py-3 px-6 font-semibold">
                        📤 Upload File Excel
                    </button>
                    <button onclick="showTab('manual')" id="tab-manual" 
                            class="tab-button border-b-2 border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 py-3 px-6 font-semibold">
                        ✍️ Nhập thủ công
                    </button>
                </nav>
            </div>
        </div>

        <!-- Tab Upload File -->
        <div id="content-upload" class="tab-content">
            <x-card>
                <x-slot:header>
                    <h3 class="text-lg font-bold">📤 Upload file Excel/CSV</h3>
                </x-slot:header>

                <form method="POST" action="{{ route('orders.create.bulk.store') }}" enctype="multipart/form-data">
                    @csrf

                    <div class="space-y-4">
                        <!-- Hướng dẫn -->
                        <div class="bg-yellow-50 border-l-4 border-yellow-400 p-4">
                            <div class="flex items-start gap-3">
                                <svg class="w-6 h-6 text-yellow-600 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                                <div class="text-sm text-yellow-800">
                                    <p class="font-semibold mb-2">Hướng dẫn sử dụng:</p>
                                    <ol class="list-decimal list-inside space-y-1">
                                        <li>Tải file mẫu Excel về máy</li>
                                        <li>Điền thông tin đơn hàng theo định dạng</li>
                                        <li>Upload file lên hệ thống</li>
                                        <li>Hệ thống sẽ tự động tạo các đơn hàng</li>
                                    </ol>
                                </div>
                            </div>
                        </div>

                        <!-- Download template -->
                        <div class="text-center">
                            <a href="#" class="btn btn-secondary inline-flex items-center gap-2">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                </svg>
                                Tải file mẫu Excel
                            </a>
                        </div>

                        <!-- Upload area -->
                        <div class="border-2 border-dashed border-gray-300 rounded-lg p-12 text-center hover:border-orange-500 transition-colors">
                            <input type="file" name="file" id="fileInput" accept=".xlsx,.xls,.csv" class="hidden" required onchange="updateFileName()">
                            <label for="fileInput" class="cursor-pointer">
                                <svg class="w-16 h-16 mx-auto text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"/>
                                </svg>
                                <p class="text-lg font-semibold text-gray-700 mb-1">Kéo thả file hoặc click để chọn</p>
                                <p class="text-sm text-gray-500">Hỗ trợ: .xlsx, .xls, .csv (Tối đa 10MB)</p>
                                <p id="fileName" class="text-sm text-orange-600 font-semibold mt-2"></p>
                            </label>
                        </div>

                        <button type="submit" class="btn btn-primary w-full btn-lg">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"/>
                            </svg>
                            Upload và tạo đơn
                        </button>
                    </div>
                </form>
            </x-card>
        </div>

        <!-- Tab Nhập thủ công -->
        <div id="content-manual" class="tab-content hidden">
            <x-card>
                <div class="text-center py-12">
                    <div class="text-6xl mb-4">🚧</div>
                    <h3 class="text-xl font-bold text-gray-900 mb-2">Tính năng đang phát triển</h3>
                    <p class="text-gray-600 mb-6">Form nhập thủ công nhiều đơn đang được xây dựng.</p>
                    <p class="text-sm text-gray-500">Vui lòng sử dụng tính năng Upload File Excel hoặc tạo từng đơn riêng lẻ.</p>
                    <a href="{{ route('orders.create.individual') }}" class="btn btn-primary mt-6 inline-flex items-center gap-2">
                        📦 Tạo đơn đơn lẻ
                    </a>
                </div>
            </x-card>
        </div>
    </div>

    <x-slot:scripts>
        <script>
            function showTab(tab) {
                // Hide all
                document.querySelectorAll('.tab-content').forEach(el => el.classList.add('hidden'));
                document.querySelectorAll('.tab-button').forEach(el => {
                    el.classList.remove('border-orange-600', 'text-orange-600');
                    el.classList.add('border-transparent', 'text-gray-500');
                });

                // Show selected
                document.getElementById('content-' + tab).classList.remove('hidden');
                const tabBtn = document.getElementById('tab-' + tab);
                tabBtn.classList.add('border-orange-600', 'text-orange-600');
                tabBtn.classList.remove('border-transparent', 'text-gray-500');
            }

            function updateFileName() {
                const input = document.getElementById('fileInput');
                const fileName = document.getElementById('fileName');
                if (input.files.length > 0) {
                    fileName.textContent = '📄 ' + input.files[0].name;
                }
            }
        </script>
    </x-slot:scripts>
</x-layout>
