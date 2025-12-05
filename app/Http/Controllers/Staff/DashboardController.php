<?php

namespace App\Http\Controllers\Staff;

use App\Http\Controllers\Controller;
use App\Models\Order;

class DashboardController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        
        // Thống kê đơn hàng được phân công
        $stats = [
            'total' => $user->assignedOrders()->count(),
            'pending' => $user->assignedOrders()->whereIn('status', ['pending', 'confirmed'])->count(),
            'in_transit' => $user->assignedOrders()->whereIn('status', ['picked_up', 'in_transit', 'out_delivery'])->count(),
            'delivered' => $user->assignedOrders()->where('status', 'delivered')->count(),
            'failed' => $user->assignedOrders()->whereIn('status', Order::FAILURE_STATUSES)->count(),
        ];
        
        return view('staff.dashboard', compact('stats'));
    }
}
