<?php

namespace App\Http\Controllers\Staff;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\ShipmentHistory;
use Illuminate\Http\Request;

class StatusUpdateController extends Controller
{
    /**
     * Hiển thị form cập nhật trạng thái
     */
    public function edit(Order $order)
    {
        // Kiểm tra xem đơn hàng có được phân công cho nhân viên này không
        if ($order->assigned_to !== auth()->id()) {
            abort(403, 'Bạn không có quyền cập nhật đơn hàng này.');
        }

        // Lấy lịch sử cập nhật
        $histories = $order->shipmentHistories()->with('updatedByUser')->get();

        return view('staff.orders.status-update', compact('order', 'histories'));
    }

    /**
     * Xử lý cập nhật trạng thái
     */
    public function update(Request $request, Order $order)
    {
        // Kiểm tra quyền
        if ($order->assigned_to !== auth()->id()) {
            abort(403, 'Bạn không có quyền cập nhật đơn hàng này.');
        }

        // Validate dữ liệu
        $validated = $request->validate([
            'status' => 'required|in:pending,confirmed,picked_up,in_transit,out_delivery,delivered,cancelled,returned',
            'location' => 'nullable|string|max:255',
            'notes' => 'nullable|string|max:1000',
        ]);

        // Cập nhật trạng thái đơn hàng
        $order->update([
            'status' => $validated['status'],
        ]);

        // Nếu đơn hàng đã giao, cập nhật thời gian giao
        if ($validated['status'] === 'delivered') {
            $order->update(['delivered_at' => now()]);
        }

        // Lưu lịch sử cập nhật
        ShipmentHistory::create([
            'order_id' => $order->id,
            'status' => $validated['status'],
            'location' => $validated['location'] ?? null,
            'notes' => $validated['notes'] ?? null,
            'updated_by' => auth()->id(),
            'happened_at' => now(),
        ]);

        // Gửi thông báo cho khách hàng
        if ($order->user_id) {
            $statusLabels = Order::STATUS_LABELS;
            $statusText = $statusLabels[$validated['status']] ?? $validated['status'];
            
            \App\Models\Notification::create([
                'user_id' => $order->user_id,
                'type' => 'status_update',
                'title' => '🔔 Cập nhật trạng thái đơn hàng',
                'message' => "Đơn hàng #{$order->order_number} đã được cập nhật: {$statusText}. " . 
                            ($validated['location'] ? "Vị trí: {$validated['location']}" : ''),
                'data' => [
                    'order_id' => $order->id,
                    'order_number' => $order->order_number,
                    'status' => $validated['status'],
                    'status_label' => $statusText,
                    'location' => $validated['location'],
                ],
            ]);
        }

        return redirect()
            ->route('staff.orders.status.edit', $order)
            ->with('success', 'Đã cập nhật trạng thái đơn hàng thành công!');
    }
}
