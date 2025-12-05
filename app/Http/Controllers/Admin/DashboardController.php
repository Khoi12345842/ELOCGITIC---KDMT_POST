<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\User;
use App\Models\Notification;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        // Thống kê tổng quan
        $stats = [
            'total_users' => User::where('role', 'customer')->count(),
            'total_staff' => User::where('role', 'staff')->count(),
            'total_orders' => Order::count(),
            'total_revenue' => Order::where('status', 'delivered')->sum('total_amount'),
            
            // Đơn hàng theo trạng thái
            'pending_orders' => Order::whereIn('status', ['pending', 'confirmed'])->count(),
            'in_transit_orders' => Order::whereIn('status', ['picked_up', 'in_transit', 'out_delivery'])->count(),
            'delivered_orders' => Order::where('status', 'delivered')->count(),
            'failed_orders' => Order::whereIn('status', Order::FAILURE_STATUSES)->count(),
            
            // Người dùng theo loại
            'individual_users' => User::where('user_type', 'individual')->where('role', 'customer')->count(),
            'business_users' => User::where('user_type', 'business')->where('role', 'customer')->count(),
        ];

        // Đơn hàng gần đây
        $recentOrders = Order::with(['user', 'assignedStaff'])
            ->orderBy('created_at', 'desc')
            ->take(10)
            ->get();

        // Thống kê theo tháng (6 tháng gần nhất)
        $monthlyStats = Order::select(
            DB::raw('strftime("%Y-%m", created_at) as month'),
            DB::raw('COUNT(*) as total'),
            DB::raw('SUM(CASE WHEN status = "delivered" THEN total_amount ELSE 0 END) as revenue')
        )
        ->where('created_at', '>=', now()->subMonths(6))
        ->groupBy('month')
        ->orderBy('month', 'desc')
        ->get();

        // Top nhân viên (theo số đơn hoàn thành)
        $topStaff = User::where('role', 'staff')
            ->withCount(['assignedOrders as completed_orders' => function ($query) {
                $query->where('status', 'delivered');
            }])
            ->orderBy('completed_orders', 'desc')
            ->take(5)
            ->get();

        // Top khách hàng (theo tổng chi tiêu)
        $topCustomers = User::where('role', 'customer')
            ->withSum(['orders as total_spent' => function ($query) {
                $query->where('status', 'delivered');
            }], 'total_amount')
            ->orderBy('total_spent', 'desc')
            ->take(5)
            ->get();

        return view('admin.dashboard', compact(
            'stats',
            'recentOrders',
            'monthlyStats',
            'topStaff',
            'topCustomers'
        ));
    }
}
