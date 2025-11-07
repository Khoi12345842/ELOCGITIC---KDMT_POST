<?php

namespace App\Http\Controllers;

    use App\Models\Order;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    // Danh sách đơn hàng
    public function index()
    {
        // Lấy đơn của user hiện tại + đơn fake để đa dạng
        $orders = Order::with('shipment')
            ->where(function($query) {
                // Đơn của user hiện tại
                $query->where('user_id', auth()->id())
                      // HOẶC đơn fake (để hiển thị cùng cho đa dạng)
                      ->orWhere('order_type', 'fake');
            })
            ->orderBy('created_at', 'desc')
            ->paginate(20);
            
        return view('orders.index', compact('orders'));
    }
    
    // Chi tiết đơn hàng
    public function show($id)
    {
        $order = Order::with(['shipment.histories']  )->findOrFail($id);
        
        return view('orders.show', compact('order'));
    }
    
    // Tracking đơn hàng
    public function track(Request $request)
    {
        $trackingNumber = $request->input('tracking_number');
        
        if (!$trackingNumber) {
            return view('orders.track');
        }
        
        $shipment = \App\Models\Shipment::with(['order', 'histories'])
            ->where('tracking_number', $trackingNumber)
            ->first();
            
        if (!$shipment) {
            return back()->with('error', 'Không tìm thấy mã vận đơn');
        }
        
        return view('orders.track', compact('shipment'));
    }
    
    // Dự đoán chi phí vận chuyển
    public function estimate(Request $request)
    {
        // Nếu chưa submit form, hiển thị form
        if (!$request->has(['from_province', 'to_province', 'weight'])) {
            return view('orders.estimate');
        }
        
        // Validate input
        $validated = $request->validate([
            'from_province' => 'required|string',
            'to_province' => 'required|string',
            'weight' => 'required|numeric|min:0.1|max:1000',
            'is_express' => 'nullable|boolean',
        ]);
        
        // Tính phí (fake logic đơn giản)
        $basePrice = 20000; // 20k phí cơ bản
        $weightPrice = $validated['weight'] * 5000; // 5k/kg
        
        // Phí khoảng cách (fake)
        $distanceFee = $this->calculateDistanceFee(
            $validated['from_province'],
            $validated['to_province']
        );
        
        // Phí hỏa tốc
        $expressFee = ($request->input('is_express') == '1') ? 30000 : 0;
        
        $totalPrice = $basePrice + $weightPrice + $distanceFee + $expressFee;
        
        // Thời gian dự kiến
        $estimatedDays = $this->calculateEstimatedDays(
            $validated['from_province'],
            $validated['to_province'],
            $request->input('is_express') == '1'
        );
        
        return view('orders.estimate', compact('validated', 'totalPrice', 'estimatedDays'));
    }
    
    // Helper: Tính phí theo khoảng cách (fake)
    private function calculateDistanceFee($from, $to)
    {
        $sameCities = ['Hà Nội', 'TP.HCM', 'Đà Nẵng'];
        
        if (in_array($from, $sameCities) && in_array($to, $sameCities)) {
            return 15000; // Nội thành lớn
        }
        
        if ($from === $to) {
            return 10000; // Cùng tỉnh
        }
        
        return rand(30000, 80000); // Liên tỉnh
    }
    
    // Helper: Tính thời gian giao (fake)
    private function calculateEstimatedDays($from, $to, $isExpress)
    {
        $baseDays = ($from === $to) ? 1 : rand(2, 5);
        
        return $isExpress ? max(1, $baseDays - 1) : $baseDays;
    }
}
