@extends('admin.layout')

@section('title', 'Thêm người dùng')

@section('content')
<div class="mb-8">
    <h1 class="text-3xl font-bold text-gray-900">➕ Thêm người dùng mới</h1>
    <p class="text-gray-600 mt-2">Tạo tài khoản người dùng mới trong hệ thống</p>
</div>

<div class="bg-white rounded-lg shadow p-6 max-w-3xl">
    <form action="{{ route('admin.users.store') }}" method="POST">
        @csrf

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">Họ tên *</label>
                <input 
                    type="text" 
                    name="name" 
                    value="{{ old('name') }}"
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-500"
                    required
                >
                @error('name')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">Email *</label>
                <input 
                    type="email" 
                    name="email" 
                    value="{{ old('email') }}"
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-500"
                    required
                >
                @error('email')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">Mật khẩu *</label>
                <input 
                    type="password" 
                    name="password" 
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-500"
                    required
                >
                @error('password')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">Xác nhận mật khẩu *</label>
                <input 
                    type="password" 
                    name="password_confirmation" 
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-500"
                    required
                >
            </div>

            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">Vai trò *</label>
                <select name="role" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-500" required>
                    <option value="customer" {{ old('role') === 'customer' ? 'selected' : '' }}>Khách hàng</option>
                    <option value="staff" {{ old('role') === 'staff' ? 'selected' : '' }}>Nhân viên</option>
                    <option value="admin" {{ old('role') === 'admin' ? 'selected' : '' }}>Admin</option>
                </select>
            </div>

            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">Loại tài khoản *</label>
                <select name="user_type" id="user_type" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-500" required>
                    <option value="individual" {{ old('user_type') === 'individual' ? 'selected' : '' }}>Cá nhân</option>
                    <option value="business" {{ old('user_type') === 'business' ? 'selected' : '' }}>Doanh nghiệp</option>
                </select>
            </div>

            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">Số điện thoại *</label>
                <input 
                    type="text" 
                    name="phone" 
                    value="{{ old('phone') }}"
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-500"
                    required
                >
            </div>

            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">Địa chỉ</label>
                <input 
                    type="text" 
                    name="address" 
                    value="{{ old('address') }}"
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-500"
                >
            </div>
        </div>

        <!-- Business fields (hidden by default) -->
        <div id="business_fields" class="mt-6 p-4 bg-orange-50 rounded-lg" style="display: {{ old('user_type') === 'business' ? 'block' : 'none' }};">
            <h3 class="font-semibold text-gray-900 mb-4">Thông tin doanh nghiệp</h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Tên công ty *</label>
                    <input 
                        type="text" 
                        name="company_name" 
                        value="{{ old('company_name') }}"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-500"
                    >
                </div>
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Mã số thuế</label>
                    <input 
                        type="text" 
                        name="tax_code" 
                        value="{{ old('tax_code') }}"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-500"
                    >
                </div>
                <div class="md:col-span-2">
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Địa chỉ công ty</label>
                    <input 
                        type="text" 
                        name="company_address" 
                        value="{{ old('company_address') }}"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-500"
                    >
                </div>
            </div>
        </div>

        <div class="mt-6 flex gap-4">
            <button type="submit" class="bg-purple-600 text-white px-6 py-3 rounded-lg font-semibold hover:bg-purple-700 transition">
                ✅ Tạo người dùng
            </button>
            <a href="{{ route('admin.users.index') }}" class="bg-gray-200 text-gray-700 px-6 py-3 rounded-lg font-semibold hover:bg-gray-300 transition">
                ❌ Hủy
            </a>
        </div>
    </form>
</div>

<script>
    document.getElementById('user_type').addEventListener('change', function() {
        const businessFields = document.getElementById('business_fields');
        if (this.value === 'business') {
            businessFields.style.display = 'block';
        } else {
            businessFields.style.display = 'none';
        }
    });
</script>
@endsection
