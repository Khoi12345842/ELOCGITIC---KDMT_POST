<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class SettingsController extends Controller
{
    /**
     * Show settings page
     */
    public function index()
    {
        return view('settings.index');
    }

    /**
     * Update profile information
     */
    public function updateProfile(Request $request)
    {
        $user = auth()->user();

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'required|string|max:20',
            'address' => 'nullable|string|max:500',
        ]);

        // Nếu là doanh nghiệp, validate thêm
        if ($user->isBusiness()) {
            $businessData = $request->validate([
                'company_name' => 'required|string|max:255',
                'tax_code' => 'required|string|max:20',
            ]);
            $validated = array_merge($validated, $businessData);
        }

        $user->update($validated);

        return back()->with('success', '✅ Cập nhật thông tin thành công!');
    }

    /**
     * Update password
     */
    public function updatePassword(Request $request)
    {
        $validated = $request->validate([
            'current_password' => 'required',
            'password' => ['required', 'confirmed', Password::min(8)],
        ]);

        $user = auth()->user();

        // Kiểm tra mật khẩu hiện tại
        if (!Hash::check($validated['current_password'], $user->password)) {
            return back()->withErrors([
                'current_password' => 'Mật khẩu hiện tại không chính xác.',
            ]);
        }

        // Cập nhật mật khẩu mới
        $user->update([
            'password' => Hash::make($validated['password']),
        ]);

        return back()->with('success', '🔒 Đổi mật khẩu thành công!');
    }

    /**
     * Update notification preferences
     */
    public function updateNotifications(Request $request)
    {
        $user = auth()->user();

        $validated = $request->validate([
            'email_notifications' => 'boolean',
            'sms_notifications' => 'boolean',
        ]);

        // Lưu vào session hoặc database (tùy thiết kế)
        session([
            'email_notifications' => $validated['email_notifications'] ?? false,
            'sms_notifications' => $validated['sms_notifications'] ?? false,
        ]);

        return back()->with('success', '🔔 Cập nhật cài đặt thông báo thành công!');
    }
}
