<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
        $stats = [
            'total' => 0,
            'pending' => 0,
            'in_transit' => 0,
            'delivered' => 0,
        ];

        // Nếu đã đăng nhập, lấy thống kê đơn hàng của user
        if (auth()->check()) {
            $userOrders = Order::where('user_id', auth()->id());
            
            $stats = [
                'total' => $userOrders->count(),
                'pending' => $userOrders->whereIn('status', ['pending', 'picked_up'])->count(),
                'in_transit' => $userOrders->whereIn('status', ['in_transit', 'out_for_delivery'])->count(),
                'delivered' => $userOrders->where('status', 'delivered')->count(),
            ];
        }

        return view('home', compact('stats'));
    }
}
