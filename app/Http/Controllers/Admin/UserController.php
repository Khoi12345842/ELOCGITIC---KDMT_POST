<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class UserController extends Controller
{
    /**
     * Danh sách người dùng
     */
    public function index(Request $request)
    {
        $filters = [
            'search' => trim((string) $request->query('search', '')),
            'role' => $request->query('role', ''),
            'user_type' => $request->query('user_type', ''),
        ];

        $query = User::query();

        if ($filters['search'] !== '') {
            $searchTerm = '%' . $filters['search'] . '%';
            $query->where(function ($q) use ($searchTerm) {
                $q->where('name', 'like', $searchTerm)
                    ->orWhere('email', 'like', $searchTerm)
                    ->orWhere('phone', 'like', $searchTerm)
                    ->orWhere('company_name', 'like', $searchTerm);
            });
        }

        if ($filters['role'] !== '') {
            $query->where('role', $filters['role']);
        }

        if ($filters['user_type'] !== '') {
            $query->where('user_type', $filters['user_type']);
        }

        $users = $query
            ->withCount('orders')
            ->orderBy('created_at', 'desc')
            ->paginate(20)
            ->withQueryString();

        $roleOptions = [
            'customer' => 'Khách hàng',
            'staff' => 'Nhân viên',
            'admin' => 'Quản trị viên',
        ];

        $userTypeOptions = [
            'individual' => 'Cá nhân',
            'business' => 'Doanh nghiệp',
        ];

        $hasFilters = collect($filters)->filter(fn($value) => $value !== '')->isNotEmpty();

        return view('admin.users.index', compact('users', 'filters', 'roleOptions', 'userTypeOptions', 'hasFilters'));
    }

    /**
     * Hiển thị form tạo user
     */
    public function create()
    {
        return view('admin.users.create');
    }

    /**
     * Lưu user mới
     */
    public function store(Request $request)
    {
        $rules = [
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => ['required', 'confirmed', Password::min(8)],
            'role' => 'required|in:customer,staff,admin',
            'user_type' => 'required|in:individual,business',
            'phone' => 'required|string|max:20',
            'address' => 'nullable|string',
        ];

        if ($request->user_type === 'business') {
            $rules['company_name'] = 'required|string|max:255';
            $rules['tax_code'] = 'nullable|string|max:50';
            $rules['company_address'] = 'nullable|string';
        }

        $validated = $request->validate($rules);

        $userData = [
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'role' => $validated['role'],
            'user_type' => $validated['user_type'],
            'phone' => $validated['phone'],
            'address' => $validated['address'],
        ];

        if ($request->user_type === 'business') {
            $userData['company_name'] = $request->company_name;
            $userData['tax_code'] = $request->tax_code;
            $userData['company_address'] = $request->company_address;
        }

        User::create($userData);

        return redirect()->route('admin.users.index')
            ->with('success', 'Đã tạo người dùng thành công!');
    }

    /**
     * Hiển thị chi tiết user
     */
    public function show(User $user)
    {
        $user->load(['orders' => function ($query) {
            $query->orderBy('created_at', 'desc')->take(10);
        }]);

        $stats = [
            'total_orders' => $user->orders()->count(),
            'completed_orders' => $user->orders()->where('status', 'delivered')->count(),
            'total_spent' => $user->orders()->where('status', 'delivered')->sum('total_amount'),
        ];

        if ($user->isStaff()) {
            $stats = [
                'assigned_orders' => $user->assignedOrders()->count(),
                'completed_orders' => $user->assignedOrders()->where('status', 'delivered')->count(),
                'pending_orders' => $user->assignedOrders()->whereIn('status', ['pending', 'confirmed'])->count(),
            ];
        }

        return view('admin.users.show', compact('user', 'stats'));
    }

    /**
     * Hiển thị form chỉnh sửa
     */
    public function edit(User $user)
    {
        return view('admin.users.edit', compact('user'));
    }

    /**
     * Cập nhật user
     */
    public function update(Request $request, User $user)
    {
        $rules = [
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'role' => 'required|in:customer,staff,admin',
            'user_type' => 'required|in:individual,business',
            'phone' => 'required|string|max:20',
            'address' => 'nullable|string',
        ];

        if ($request->filled('password')) {
            $rules['password'] = ['confirmed', Password::min(8)];
        }

        if ($request->user_type === 'business') {
            $rules['company_name'] = 'required|string|max:255';
            $rules['tax_code'] = 'nullable|string|max:50';
            $rules['company_address'] = 'nullable|string';
        }

        $validated = $request->validate($rules);

        $userData = [
            'name' => $validated['name'],
            'email' => $validated['email'],
            'role' => $validated['role'],
            'user_type' => $validated['user_type'],
            'phone' => $validated['phone'],
            'address' => $validated['address'],
        ];

        if ($request->filled('password')) {
            $userData['password'] = Hash::make($validated['password']);
        }

        if ($request->user_type === 'business') {
            $userData['company_name'] = $request->company_name;
            $userData['tax_code'] = $request->tax_code;
            $userData['company_address'] = $request->company_address;
        }

        $user->update($userData);

        return redirect()->route('admin.users.index')
            ->with('success', 'Đã cập nhật người dùng thành công!');
    }

    /**
     * Xóa user
     */
    public function destroy(User $user)
    {
        // Không cho phép xóa chính mình
        if ($user->id === auth()->id()) {
            return back()->with('error', 'Bạn không thể xóa chính mình!');
        }

        // Xóa user (orders sẽ tự động xóa do cascade)
        $user->delete();

        return redirect()->route('admin.users.index')
            ->with('success', 'Đã xóa người dùng thành công!');
    }

    /**
     * Kích hoạt/vô hiệu hóa user
     */
    public function toggleStatus(User $user)
    {
        // Thêm column is_active nếu cần
        // $user->update(['is_active' => !$user->is_active]);
        
        return back()->with('success', 'Đã cập nhật trạng thái người dùng!');
    }
}
