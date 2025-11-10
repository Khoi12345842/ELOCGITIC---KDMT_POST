<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

class LoginController extends Controller
{
    /**
     * Show login form
     */
    public function showLoginForm()
    {
        return view('auth.login');
    }

    /**
     * Handle login
     */
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
            'user_type' => 'required|in:individual,business',
        ]);

        // Thử đăng nhập
        if (Auth::attempt(['email' => $credentials['email'], 'password' => $credentials['password']], $request->filled('remember'))) {
            $user = Auth::user();

            if ($user->role === User::ROLE_STAFF) {
                Auth::logout();

                return redirect()->route('staff.login')
                    ->withErrors(['email' => 'Tài khoản này thuộc phân hệ nhân viên. Vui lòng đăng nhập tại cổng nhân viên.'])
                    ->withInput($request->only('email'));
            }
            
            // Kiểm tra loại tài khoản có khớp không
            if ($user->user_type !== $credentials['user_type']) {
                Auth::logout();
                
                $expectedType = $credentials['user_type'] === 'individual' ? 'cá nhân' : 'doanh nghiệp';
                $actualType = $user->user_type === 'individual' ? 'cá nhân' : 'doanh nghiệp';
                
                return back()->withErrors([
                    'user_type' => "Tài khoản này là loại {$actualType}, không phải {$expectedType}. Vui lòng chọn đúng loại tài khoản.",
                ])->withInput($request->only('email', 'user_type'));
            }

            $request->session()->regenerate();

            $userName = $user->name;
            $userType = $user->isBusiness() ? 'Doanh nghiệp' : 'Cá nhân';

            return redirect()->intended(route('orders.index'))
                ->with('success', "Chào mừng {$userType} {$userName}!");
        }

        return back()->withErrors([
            'email' => 'Email hoặc mật khẩu không chính xác.',
        ])->withInput($request->only('email', 'user_type'));
    }

    /**
     * Handle logout
     */
    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login')->with('success', 'Đã đăng xuất thành công. Hẹn gặp lại!');
    }
}
