<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Shipment;
use App\Models\ShipmentHistory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CreateOrderController extends Controller
{
    /**
     * Show form lên đơn cho individual user
     */
    public function showIndividualForm()
    {
        return view('orders.create-individual');
    }

    /**
     * Xử lý tạo đơn individual (manual)
     */
    public function storeIndividual(Request $request)
    {
        $validated = $request->validate([
            'sender_name' => 'required|string|max:255',
            'sender_phone' => 'required|string|max:20',
            'sender_address' => 'required|string',
            'sender_city' => 'required|string|max:100',
            
            'receiver_name' => 'required|string|max:255',
            'receiver_phone' => 'required|string|max:20',
            'receiver_address' => 'required|string',
            'receiver_city' => 'required|string|max:100',
            
            'package_description' => 'nullable|string|max:500',
            'weight' => 'required|numeric|min:0.1|max:50',
            'cod_amount' => 'nullable|numeric|min:0',
            'notes' => 'nullable|string|max:1000',
        ]);

        DB::beginTransaction();
        try {
            // Tính phí vận chuyển
            $shippingFee = $this->calculateShippingFee(
                $validated['weight'],
                $validated['sender_city'],
                $validated['receiver_city']
            );

            $codAmount = $validated['cod_amount'] ?? 0;
            $totalAmount = $shippingFee + $codAmount;

            // Tự động chọn nhân viên để phân công (round-robin hoặc random)
            $assignedStaff = \App\Models\User::where('role', 'staff')
                ->inRandomOrder()
                ->first();

            // Tạo order
            $order = Order::create([
                'order_number' => $this->generateOrderNumber(),
                'user_id' => auth()->id(),
                'order_type' => 'manual',
                'sender_name' => $validated['sender_name'],
                'sender_phone' => $validated['sender_phone'],
                'sender_address' => $validated['sender_address'],
                'sender_city' => $validated['sender_city'],
                'receiver_name' => $validated['receiver_name'],
                'receiver_phone' => $validated['receiver_phone'],
                'receiver_address' => $validated['receiver_address'],
                'receiver_city' => $validated['receiver_city'],
                'package_description' => $validated['package_description'],
                'weight' => $validated['weight'],
                'cod_amount' => $codAmount,
                'shipping_fee' => $shippingFee,
                'total_amount' => $totalAmount,
                'status' => 'pending',
                'notes' => $validated['notes'],
                'assigned_to' => $assignedStaff?->id, // Phân công tự động
                'route_code' => $this->generateRouteCode($validated['sender_city'], $validated['receiver_city']),
                'scheduled_date' => now()->addDay(), // Dự kiến giao ngày mai
            ]);

            // Tạo shipment
            $shipment = Shipment::create([
                'order_id' => $order->id,
                'tracking_number' => $this->generateTrackingNumber(),
                'current_location' => 'Chờ lấy hàng',
                'current_status' => 'Chờ xử lý',
                'status_description' => 'Đơn hàng đã được tạo và đang chờ xác nhận',
            ]);

            // Tạo lịch sử đầu tiên
            ShipmentHistory::create([
                'order_id' => $order->id,
                'status' => 'pending',
                'location' => 'Hệ thống',
                'notes' => 'Đơn hàng của bạn đã được tạo thành công và đang chờ xác nhận.',
                'updated_by' => auth()->id(),
                'happened_at' => now(),
            ]);

            // Gửi thông báo cho nhân viên được phân công
            if ($assignedStaff) {
                \App\Models\Notification::create([
                    'user_id' => $assignedStaff->id,
                    'type' => 'new_order',
                    'title' => '📦 Đơn hàng mới được phân công',
                    'message' => "Bạn có đơn hàng mới #{$order->order_number} từ {$order->sender_city} đến {$order->receiver_city}. Khối lượng: {$order->weight}kg",
                    'data' => [
                        'order_id' => $order->id,
                        'order_number' => $order->order_number,
                        'route_code' => $order->route_code,
                    ],
                ]);
            }

            DB::commit();

            return redirect()->route('orders.show', $order->id)
                ->with('success', '✅ Tạo đơn hàng thành công! Mã đơn: ' . $order->order_number);

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withErrors(['error' => 'Có lỗi xảy ra: ' . $e->getMessage()])->withInput();
        }
    }

    /**
     * Show form lên đơn theo lô cho business user
     */
    public function showBulkForm()
    {
        if (!auth()->user()->isBusiness()) {
            return redirect()->route('orders.create.individual')
                ->with('error', 'Chỉ tài khoản doanh nghiệp mới có thể lên đơn theo lô.');
        }

        // Lấy đơn hàng từ shop (nếu có liên kết)
        $shopOrders = Order::where('user_id', auth()->id())
            ->where('order_type', 'shop_sync')
            ->orderBy('created_at', 'desc')
            ->take(10)
            ->get();
        
        $shopOrderCount = Order::where('user_id', auth()->id())
            ->where('order_type', 'shop_sync')
            ->count();

        return view('orders.create-bulk', compact('shopOrders', 'shopOrderCount'));
    }

    /**
     * Xử lý upload file Excel/CSV để lên đơn theo lô
     */
    public function storeBulk(Request $request)
    {
        if (!auth()->user()->isBusiness()) {
            return back()->with('error', 'Chỉ tài khoản doanh nghiệp mới có thể lên đơn theo lô.');
        }

        $validated = $request->validate([
            'file' => 'required|file|mimes:xlsx,xls,csv|max:10240', // Max 10MB
        ]);

        // TODO: Xử lý file Excel/CSV (sẽ làm sau)
        return back()->with('info', 'Tính năng upload file đang được phát triển. Vui lòng sử dụng form nhập từng đơn.');
    }

    /**
     * Generate order number
     */
    private function generateOrderNumber(): string
    {
        return 'ORD' . now()->format('Ymd') . str_pad(Order::whereDate('created_at', today())->count() + 1, 4, '0', STR_PAD_LEFT);
    }

    /**
     * Generate tracking number
     */
    private function generateTrackingNumber(): string
    {
        return 'FS' . strtoupper(substr(md5(uniqid()), 0, 10));
    }

    /**
     * Generate route code based on cities
     */
    private function generateRouteCode(string $fromCity, string $toCity): string
    {
        $cityAbbr = [
            'Hà Nội' => 'HN',
            'TP.HCM' => 'HCM',
            'Đà Nẵng' => 'DN',
            'Hải Phòng' => 'HP',
            'Cần Thơ' => 'CT',
        ];
        
        $from = $cityAbbr[$fromCity] ?? substr($fromCity, 0, 2);
        $to = $cityAbbr[$toCity] ?? substr($toCity, 0, 2);
        
        return strtoupper($from . '-' . $to);
    }

    /**
     * Tính phí vận chuyển (giống như estimate)
     */
    private function calculateShippingFee($weight, $fromCity, $toCity): float
    {
        $baseFee = 15000; // Phí cơ bản
        $weightFee = $weight * 5000; // 5k/kg
        
        // Phí theo khoảng cách (giả lập)
        $cities = ['Hà Nội', 'TP.HCM', 'Đà Nẵng', 'Cần Thơ', 'Hải Phòng'];
        $fromIndex = array_search($fromCity, $cities) !== false ? array_search($fromCity, $cities) : 0;
        $toIndex = array_search($toCity, $cities) !== false ? array_search($toCity, $cities) : 0;
        $distance = abs($fromIndex - $toIndex);
        $distanceFee = $distance * 10000;

        $total = $baseFee + $weightFee + $distanceFee;

        // Giảm giá cho business user
        if (auth()->user()->isBusiness()) {
            $discountRate = auth()->user()->discount_rate ?? 0;
            $total = $total * (1 - $discountRate / 100);
        }

        return round($total, -3); // Làm tròn đến nghìn
    }
}
