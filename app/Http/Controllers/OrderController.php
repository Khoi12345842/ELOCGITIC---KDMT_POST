<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    // Danh sách đơn hàng
    public function index(Request $request)
    {
        $filters = [
            'search' => trim((string) $request->query('search', '')),
            'status' => $request->query('status', ''),
            'type' => $request->query('type', ''),
            'date_from' => $request->query('date_from', ''),
            'date_to' => $request->query('date_to', ''),
        ];

        $query = Order::with('shipment')
            ->where(function ($query) {
                $query->where('user_id', auth()->id())
                    ->orWhere('order_type', 'fake');
            });

        if ($filters['search'] !== '') {
            $searchTerm = '%' . $filters['search'] . '%';

            $query->where(function ($query) use ($searchTerm) {
                $query->where('order_number', 'like', $searchTerm)
                    ->orWhere('receiver_name', 'like', $searchTerm)
                    ->orWhere('receiver_phone', 'like', $searchTerm)
                    ->orWhere('receiver_address', 'like', $searchTerm)
                    ->orWhereHas('shipment', function ($shipmentQuery) use ($searchTerm) {
                        $shipmentQuery->where('tracking_number', 'like', $searchTerm);
                    });
            });
        }

        if ($filters['status'] !== '') {
            $query->where('status', $filters['status']);
        }

        if ($filters['type'] !== '') {
            $query->where('order_type', $filters['type']);
        }

        if ($filters['date_from'] !== '') {
            $query->whereDate('created_at', '>=', $filters['date_from']);
        }

        if ($filters['date_to'] !== '') {
            $query->whereDate('created_at', '<=', $filters['date_to']);
        }

        $orders = $query
            ->orderBy('created_at', 'desc')
            ->paginate(20)
            ->withQueryString();

        $statusOptions = Order::STATUS_LABELS;

        $typeOptions = [
            'manual' => 'Đơn thủ công',
            'bulk' => 'Đơn theo lô',
            'shop_sync' => 'Đồng bộ từ shop',
            'fake' => 'Đơn demo',
        ];

        $hasFilters = collect($filters)
            ->except('search')
            ->filter(fn ($value) => $value !== '')
            ->isNotEmpty() || $filters['search'] !== '';

        return view('orders.index', compact('orders', 'filters', 'statusOptions', 'typeOptions', 'hasFilters'));
    }
    
    // Chi tiết đơn hàng
    public function show($id)
    {
        $order = Order::with(['shipment', 'shipmentHistories.updatedByUser'])->findOrFail($id);
        
        return view('orders.show', compact('order'));
    }
    
    // Tracking đơn hàng
    public function track(Request $request)
    {
        $trackingNumber = $request->input('tracking_number');
        
        if (!$trackingNumber) {
            return view('orders.track');
        }
        
        $shipment = \App\Models\Shipment::with(['order.shipmentHistories.updatedByUser'])
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
