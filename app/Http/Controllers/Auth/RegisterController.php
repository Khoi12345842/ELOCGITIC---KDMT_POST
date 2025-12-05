<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class RegisterController extends Controller
{
    /**
     * Show registration form
     */
    public function showRegistrationForm()
    {
        return view('auth.register');
    }

    /**
     * Handle registration
     */
    public function register(Request $request)
    {
        // Validate common fields
        $rules = [
            'user_type' => 'required|in:individual,business',
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'phone' => 'required|string|max:20',
            'address' => 'required|string',
        ];

        // Thêm validation cho business user
        if ($request->user_type === 'business') {
            $rules['company_name'] = 'required|string|max:255';
            $rules['tax_code'] = 'required|string|max:50|unique:users,tax_code';
            $rules['company_address'] = 'required|string';
        }

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        // Tạo user
        $userData = [
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'user_type' => $request->user_type,
            'phone' => $request->phone,
            'address' => $request->address,
        ];

        // Thêm thông tin business nếu là doanh nghiệp
        if ($request->user_type === 'business') {
            $userData['company_name'] = $request->company_name;
            $userData['tax_code'] = $request->tax_code;
            $userData['company_address'] = $request->company_address;
            
            // Fake shop info cho business user
            $platforms = ['Shopee', 'Lazada', 'TikTok Shop', 'Sendo'];
            $userData['shop_platform'] = $platforms[array_rand($platforms)];
            $userData['shop_name'] = $request->company_name . ' Official';
            $userData['shop_id'] = 'SHOP' . strtoupper(substr(md5($request->email), 0, 8));
            
            // Chính sách giảm giá mặc định cho business
            $userData['discount_rate'] = 5.00; // 5% discount
        }

        $user = User::create($userData);

        // Đăng nhập luôn sau khi đăng ký
        Auth::login($user);

        return redirect()->route('orders.index')->with('success', 'Đăng ký thành công! Chào mừng đến với FastShip.');
    }
}
