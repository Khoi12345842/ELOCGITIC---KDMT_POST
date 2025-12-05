<?php

namespace App\Http\Controllers\Staff;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function index(Request $request)
    {
        $filters = [
            'search' => trim((string) $request->query('search', '')),
            'status' => $request->query('status', ''),
            'route' => $request->query('route', ''),
            'date_from' => $request->query('date_from', ''),
            'date_to' => $request->query('date_to', ''),
        ];

        $query = Order::with(['shipment', 'assignedStaff'])
            ->where('assigned_to', $request->user()->id);

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

        $statusOptions = Order::STATUS_LABELS;

        $hasFilters = collect($filters)
            ->filter(fn ($value) => $value !== '')
            ->isNotEmpty();

        return view('staff.orders.index', compact('orders', 'filters', 'statusOptions', 'hasFilters'));
    }
}
