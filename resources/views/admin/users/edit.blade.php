@extends('admin.layout')

@section('title', 'Chỉnh sửa người dùng')

@section('content')
<div class="mb-8">
    <a href="{{ route('admin.users.index') }}" class="text-purple-600 hover:text-purple-800 font-semibold mb-4 inline-block">
        ← Quay lại danh sách
    </a>
    <h1 class="text-3xl font-bold text-gray-900">✏️ Chỉnh sửa người dùng</h1>
    <p class="text-gray-600 mt-2">Cập nhật thông tin cho {{ $user->name }}</p>
</div>

<div class="bg-white rounded-lg shadow p-6 max-w-3xl">
    <form action="{{ route('admin.users.update', $user) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">Họ tên *</label>
                <input 
                    type="text" 
                    name="name" 
                    value="{{ old('name', $user->name) }}"
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
                    value="{{ old('email', $user->email) }}"
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-500"
                    required
                >
                @error('email')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">Mật khẩu mới (để trống nếu không đổi)</label>
                <input 
                    type="password" 
                    name="password" 
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-500"
                >
                @error('password')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">Xác nhận mật khẩu mới</label>
                <input 
                    type="password" 
                    name="password_confirmation" 
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-500"
                >
            </div>

            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">Vai trò *</label>
                <select name="role" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-500" required>
                    <option value="customer" {{ old('role', $user->role) === 'customer' ? 'selected' : '' }}>Khách hàng</option>
                    <option value="staff" {{ old('role', $user->role) === 'staff' ? 'selected' : '' }}>Nhân viên</option>
                    <option value="admin" {{ old('role', $user->role) === 'admin' ? 'selected' : '' }}>Admin</option>
                </select>
            </div>

            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">Loại tài khoản *</label>
                <select name="user_type" id="user_type" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-500" required>
                    <option value="individual" {{ old('user_type', $user->user_type) === 'individual' ? 'selected' : '' }}>Cá nhân</option>
                    <option value="business" {{ old('user_type', $user->user_type) === 'business' ? 'selected' : '' }}>Doanh nghiệp</option>
                </select>
            </div>

            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">Số điện thoại *</label>
                <input 
                    type="text" 
                    name="phone" 
                    value="{{ old('phone', $user->phone) }}"
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-500"
                    required
                >
            </div>

            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">Địa chỉ</label>
                <input 
                    type="text" 
                    name="address" 
                    value="{{ old('address', $user->address) }}"
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-500"
                >
            </div>
        </div>

        <!-- Business fields -->
        <div id="business_fields" class="mt-6 p-4 bg-orange-50 rounded-lg" style="display: {{ old('user_type', $user->user_type) === 'business' ? 'block' : 'none' }};">
            <h3 class="font-semibold text-gray-900 mb-4">Thông tin doanh nghiệp</h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Tên công ty *</label>
                    <input 
                        type="text" 
                        name="company_name" 
                        value="{{ old('company_name', $user->company_name) }}"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-500"
                    >
                </div>
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Mã số thuế</label>
                    <input 
                        type="text" 
                        name="tax_code" 
                        value="{{ old('tax_code', $user->tax_code) }}"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-500"
                    >
                </div>
                <div class="md:col-span-2">
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Địa chỉ công ty</label>
                    <input 
                        type="text" 
                        name="company_address" 
                        value="{{ old('company_address', $user->company_address) }}"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-500"
                    >
                </div>
            </div>
        </div>

        <div class="mt-6 flex gap-4">
            <button type="submit" class="bg-purple-600 text-white px-6 py-3 rounded-lg font-semibold hover:bg-purple-700 transition">
                ✅ Cập nhật
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
