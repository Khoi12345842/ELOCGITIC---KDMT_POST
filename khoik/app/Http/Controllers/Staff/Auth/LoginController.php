<?php

namespace App\Http\Controllers\Staff\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    public function showLoginForm()
    {
        return view('staff.auth.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        $remember = $request->boolean('remember');

        $attemptCredentials = [
            'email' => $credentials['email'],
            'password' => $credentials['password'],
            'role' => User::ROLE_STAFF,
        ];

        if (Auth::attempt($attemptCredentials, $remember)) {
            $request->session()->regenerate();

            return redirect()->intended(route('staff.dashboard'))
                ->with('success', 'Đăng nhập nhân viên thành công. Chúc bạn một ngày làm việc hiệu quả!');
        }

        return back()->withErrors([
            'email' => 'Thông tin đăng nhập không chính xác hoặc tài khoản không thuộc phân hệ nhân viên.',
        ])->withInput($request->only('email', 'password'));
    }
}
