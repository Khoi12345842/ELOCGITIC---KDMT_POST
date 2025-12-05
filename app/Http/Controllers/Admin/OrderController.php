<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\User;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function index(Request $request)
    {
        $filters = [
            'search' => trim((string) $request->query('search', '')),
            'status' => $request->query('status', ''),
            'assigned_to' => $request->query('assigned_to', ''),
            'route' => $request->query('route', ''),
            'date_from' => $request->query('date_from', ''),
            'date_to' => $request->query('date_to', ''),
        ];

        // Admin có thể xem TẤT CẢ đơn hàng
        $query = Order::with(['shipment', 'assignedStaff', 'user']);

        if ($filters['search'] !== '') {
            $searchTerm = '%' . $filters['search'] . '%';

            $query->where(function ($subQuery) use ($searchTerm) {
                $subQuery->where('order_number', 'like', $searchTerm)
                    ->orWhere('receiver_name', 'like', $searchTerm)
                    ->orWhere('receiver_phone', 'like', $searchTerm)
                    ->orWhere('route_code', 'like', $searchTerm)
                    ->orWhereHas('shipment', function ($shipmentQuery) use ($searchTerm) {
                        $shipmentQuery->where('tracking_number', 'like', $searchTerm);
                    });
            });
        }

        if ($filters['status'] !== '') {
            $query->where('status', $filters['status']);
        }

        if ($filters['assigned_to'] !== '') {
            if ($filters['assigned_to'] === 'unassigned') {
                $query->whereNull('assigned_to');
            } else {
                $query->where('assigned_to', $filters['assigned_to']);
            }
        }

        if ($filters['route'] !== '') {
            $query->where('route_code', 'like', '%' . $filters['route'] . '%');
        }

        if ($filters['date_from'] !== '') {
            $query->whereDate('scheduled_date', '>=', $filters['date_from']);
        }

        if ($filters['date_to'] !== '') {
            $query->whereDate('scheduled_date', '<=', $filters['date_to']);
        }

        $orders = $query
            ->orderByDesc('scheduled_date')
            ->orderByDesc('created_at')
            ->paginate(20)
            ->withQueryString();

        // Tính stats từ TOÀN BỘ orders (không phân trang)
        $stats = [
            'total' => Order::count(),
            'unassigned' => Order::whereNull('assigned_to')->count(),
            'in_progress' => Order::whereIn('status', ['confirmed', 'picked_up', 'in_transit', 'out_delivery'])->count(),
            'delivered' => Order::where('status', 'delivered')->count(),
        ];

        // Danh sách nhân viên để phân công
        $staffList = User::where('role', 'staff')->get();

        $statusOptions = Order::STATUS_LABELS;

        $hasFilters = collect($filters)
            ->filter(fn ($value) => $value !== '')
            ->isNotEmpty();

        return view('admin.orders.index', compact('orders', 'filters', 'statusOptions', 'hasFilters', 'staffList', 'stats'));
    }

    public function show(Order $order)
    {
        $order->load(['shipment.histories', 'assignedStaff', 'user', 'incidentReports']);
        
        return view('admin.orders.show', compact('order'));
    }

    public function assignStaff(Request $request, Order $order)
    {
        try {
            $validated = $request->validate([
                'assigned_to' => 'required|exists:users,id',
            ]);

            $staff = User::findOrFail($validated['assigned_to']);

            if ($staff->role !== 'staff' && $staff->role !== 'admin') {
                return back()->with('error', 'Chỉ có thể phân công cho nhân viên!');
            }

            $order->update([
                'assigned_to' => $validated['assigned_to'],
            ]);

            return back()->with('success', 'Đã phân công đơn hàng ' . $order->order_number . ' cho ' . $staff->name);
        } catch (\Exception $e) {
            \Log::error('Assign staff error: ' . $e->getMessage());
            return back()->with('error', 'Lỗi khi phân công: ' . $e->getMessage());
        }
    }

    public function updateStatus(Request $request, Order $order)
    {
        $request->validate([
            'status' => 'required|in:' . implode(',', array_keys(Order::STATUS_LABELS)),
            'notes' => 'nullable|string|max:500',
        ]);

        $oldStatus = $order->status;
        $order->update(['status' => $request->status]);

        // Tạo lịch sử shipment
        if ($order->shipment) {
            $order->shipment->histories()->create([
                'status' => $request->status,
                'location' => $request->location ?? 'Admin Update',
                'notes' => $request->notes ?? 'Cập nhật bởi Admin: ' . auth()->user()->name,
                'updated_by' => auth()->id(),
            ]);
        }

        return back()->with('success', 'Đã cập nhật trạng thái từ "' . Order::STATUS_LABELS[$oldStatus] . '" sang "' . Order::STATUS_LABELS[$request->status] . '"');
    }

    public function destroy(Order $order)
    {
        // Chỉ admin mới được xóa đơn hàng
        $orderNumber = $order->order_number;
        
        // Xóa shipment và histories liên quan
        if ($order->shipment) {
            $order->shipment->histories()->delete();
            $order->shipment->delete();
        }

        // Xóa incident reports
        $order->incidentReports()->delete();

        // Xóa order
        $order->delete();

        return redirect()->route('admin.orders.index')->with('success', 'Đã xóa đơn hàng ' . $orderNumber);
    }

    public function bulkAssign(Request $request)
    {
        $request->validate([
            'order_ids' => 'required|array',
            'order_ids.*' => 'exists:orders,id',
            'assigned_to' => 'required|exists:users,id',
        ]);

        $staff = User::findOrFail($request->assigned_to);

        if ($staff->role !== 'staff' && $staff->role !== 'admin') {
            return back()->with('error', 'Chỉ có thể phân công cho nhân viên!');
        }

        $count = Order::whereIn('id', $request->order_ids)->update([
            'assigned_to' => $request->assigned_to,
        ]);

        return back()->with('success', "Đã phân công {$count} đơn hàng cho {$staff->name}");
    }
}
