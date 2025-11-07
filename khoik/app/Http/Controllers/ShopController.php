<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Shipment;
use App\Models\ShipmentHistory;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ShopController extends Controller
{
    /**
     * Hiển thị form liên kết shop
     */
    public function showLinkForm()
    {
        if (!auth()->user()->isBusiness()) {
            return redirect()->route('orders.index')
                ->with('error', 'Chỉ tài khoản doanh nghiệp mới có thể liên kết shop.');
        }

        return view('shop.link');
    }

    /**
     * Xử lý liên kết shop (bao gồm cả ký hợp đồng)
     */
    public function linkShop(Request $request)
    {
        if (!auth()->user()->isBusiness()) {
            return back()->with('error', 'Chỉ tài khoản doanh nghiệp mới có thể liên kết shop.');
        }

        $validated = $request->validate([
            'shop_platform' => 'required|in:Shopee,Lazada,TikTok Shop,Sendo',
            'shop_id' => 'required|string|max:255',
            'shop_name' => 'required|string|max:255',
            'discount_rate' => 'required|numeric|min:0|max:30',
            'contract_duration' => 'required|integer|min:1|max:36',
            'agree_terms' => 'required|accepted',
        ]);

        // Ép kiểu để tránh lỗi type mismatch
        $validated['contract_duration'] = (int) $validated['contract_duration'];
        $validated['discount_rate'] = (float) $validated['discount_rate'];

        DB::beginTransaction();
        try {
            $user = auth()->user();

            // Cập nhật thông tin shop
            $user->update([
                'shop_platform' => $validated['shop_platform'],
                'shop_id' => $validated['shop_id'],
                'shop_name' => $validated['shop_name'],
                'discount_rate' => $validated['discount_rate'],
                'has_contract' => true,
                'contract_start_date' => now(),
                'contract_end_date' => now()->addMonths($validated['contract_duration']),
            ]);

            // Tạo đơn fake từ shop (5-10 đơn)
            $orderCount = rand(5, 10);
            $this->generateFakeShopOrders($user, $orderCount);

            DB::commit();

            return redirect()->route('orders.create.bulk')
                ->with('success', '🎉 Liên kết shop thành công! Đã đồng bộ ' . $orderCount . ' đơn hàng từ shop của bạn. Bạn có thể xem các đơn hàng ở phần "Đơn hàng của tôi" hoặc "Quản lý Shop".');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withErrors(['error' => 'Có lỗi xảy ra: ' . $e->getMessage()])->withInput();
        }
    }

    /**
     * Dashboard quản lý shop
     */
    public function dashboard()
    {
        if (!auth()->user()->isBusiness() || !auth()->user()->shop_id) {
            return redirect()->route('shop.link')
                ->with('info', 'Vui lòng liên kết shop trước.');
        }

        $user = auth()->user();
        
        // Lấy đơn từ shop
        $orders = Order::where('user_id', $user->id)
            ->where('order_type', 'shop_sync')
            ->with('shipment')
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        // Thống kê
        $stats = [
            'total_orders' => Order::where('user_id', $user->id)->where('order_type', 'shop_sync')->count(),
            'pending' => Order::where('user_id', $user->id)->where('order_type', 'shop_sync')->where('status', 'pending')->count(),
            'in_transit' => Order::where('user_id', $user->id)->where('order_type', 'shop_sync')->whereIn('status', ['in_transit', 'out_delivery'])->count(),
            'delivered' => Order::where('user_id', $user->id)->where('order_type', 'shop_sync')->where('status', 'delivered')->count(),
        ];

        return view('shop.dashboard', compact('orders', 'stats', 'user'));
    }

    /**
     * Đồng bộ đơn mới từ shop (fake)
     */
    public function syncOrders()
    {
        if (!auth()->user()->isBusiness() || !auth()->user()->shop_id) {
            return back()->with('error', 'Chưa liên kết shop.');
        }

        $user = auth()->user();
        $orderCount = rand(3, 7);
        
        $this->generateFakeShopOrders($user, $orderCount);

        return back()->with('success', '✅ Đã đồng bộ ' . $orderCount . ' đơn hàng mới từ ' . $user->shop_platform);
    }

    /**
     * Generate fake shop orders
     */
    private function generateFakeShopOrders(User $user, int $count)
    {
        $faker = \Faker\Factory::create('vi_VN');
        $cities = ['Hà Nội', 'TP.HCM', 'Đà Nẵng', 'Hải Phòng', 'Cần Thơ'];
        $statuses = ['pending', 'confirmed', 'picked_up', 'in_transit', 'out_delivery', 'delivered'];

        for ($i = 0; $i < $count; $i++) {
            $status = $statuses[array_rand($statuses)];
            $senderCity = $cities[array_rand($cities)];
            $receiverCity = $cities[array_rand($cities)];
            $weight = round(rand(5, 200) / 10, 1);
            $codAmount = rand(0, 1) ? rand(100000, 5000000) : 0;
            
            // Tính phí với giảm giá
            $shippingFee = $this->calculateFee($weight, $senderCity, $receiverCity);
            $shippingFee = $shippingFee * (1 - $user->discount_rate / 100);

            $order = Order::create([
                'order_number' => $this->generateOrderNumber(),
                'user_id' => $user->id,
                'order_type' => 'shop_sync',
                'shop_platform' => $user->shop_platform,
                'shop_name' => $user->shop_name,
                'shop_order_id' => strtoupper($user->shop_platform) . rand(100000, 999999),
                'sender_name' => $user->shop_name,
                'sender_phone' => $user->phone,
                'sender_address' => $user->company_address,
                'sender_city' => $senderCity,
                'receiver_name' => $faker->name,
                'receiver_phone' => '0' . rand(900000000, 999999999),
                'receiver_address' => $faker->address,
                'receiver_city' => $receiverCity,
                'package_description' => $faker->randomElement(['Quần áo', 'Mỹ phẩm', 'Điện tử', 'Đồ gia dụng', 'Sách']),
                'weight' => $weight,
                'cod_amount' => $codAmount,
                'shipping_fee' => round($shippingFee, -3),
                'total_amount' => round($shippingFee + $codAmount, -3),
                'status' => $status,
                'notes' => 'Đơn tự động từ ' . $user->shop_platform,
                'created_at' => now()->subDays(rand(0, 30)),
            ]);

            // Tạo shipment
            $shipment = Shipment::create([
                'order_id' => $order->id,
                'tracking_number' => 'FS' . strtoupper(substr(md5(uniqid()), 0, 10)),
                'current_location' => $this->getLocationByStatus($status, $order),
                'status' => $status,
                'latitude' => $faker->latitude(8, 23),
                'longitude' => $faker->longitude(102, 109),
            ]);

            // Tạo history
            $this->createShipmentHistory($order, $shipment);
        }
    }

    private function generateOrderNumber(): string
    {
        return 'ORD' . now()->format('Ymd') . str_pad(Order::whereDate('created_at', today())->count() + 1, 4, '0', STR_PAD_LEFT);
    }

    private function calculateFee($weight, $from, $to): float
    {
        $baseFee = 15000;
        $weightFee = $weight * 5000;
        $cities = ['Hà Nội', 'TP.HCM', 'Đà Nẵng', 'Cần Thơ', 'Hải Phòng'];
        $fromIndex = array_search($from, $cities) !== false ? array_search($from, $cities) : 0;
        $toIndex = array_search($to, $cities) !== false ? array_search($to, $cities) : 0;
        $distance = abs($fromIndex - $toIndex);
        $distanceFee = $distance * 10000;
        return $baseFee + $weightFee + $distanceFee;
    }

    private function getLocationByStatus($status, $order)
    {
        return match($status) {
            'pending' => 'Chờ lấy hàng',
            'confirmed' => 'Đã xác nhận',
            'picked_up' => $order->sender_city,
            'in_transit' => 'Trung tâm phân loại',
            'out_delivery' => $order->receiver_city,
            'delivered' => $order->receiver_address,
            default => 'Chờ xử lý'
        };
    }

    private function createShipmentHistory($order, $shipment)
    {
        $statuses = [
            ['status' => 'Đơn hàng từ shop đã được tạo', 'location' => 'Hệ thống'],
            ['status' => 'Đã lấy hàng', 'location' => $order->sender_city],
            ['status' => 'Đang vận chuyển', 'location' => 'Trung tâm phân loại'],
            ['status' => 'Hàng đến khu vực giao', 'location' => $order->receiver_city],
        ];
        
        $baseTime = $order->created_at;
        foreach ($statuses as $i => $item) {
            ShipmentHistory::create([
                'shipment_id' => $shipment->id,
                'status' => $item['status'],
                'location' => $item['location'],
                'description' => 'Cập nhật: ' . $item['status'],
                'updated_by' => 'Hệ thống tự động',
                'happened_at' => $baseTime->copy()->addHours(rand(2, 12) * ($i + 1)),
            ]);
        }
    }
}
