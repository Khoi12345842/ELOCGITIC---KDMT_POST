<?php

namespace App\Http\Controllers\Staff;

use App\Http\Controllers\Controller;
use App\Models\IncidentReport;
use App\Models\Order;
use Illuminate\Http\Request;

class IncidentController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();

        $failedStatuses = Order::FAILURE_STATUSES;

        $failedOrders = $user->assignedOrders()
            ->whereIn('status', $failedStatuses)
            ->with(['incidentReports' => function ($query) {
                $query->orderByDesc('created_at')->with('reporter');
            }])
            ->latest()
            ->paginate(10);

    return view('staff.incidents.index', compact('failedOrders', 'failedStatuses'));
    }

    public function store(Request $request)
    {
        $user = $request->user();

        $data = $request->validate([
            'order_id' => ['required', 'integer', 'exists:orders,id'],
            'issue_type' => ['required', 'string', 'max:120'],
            'summary' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string', 'max:2000'],
        ]);

        $order = $user->assignedOrders()
            ->where('id', $data['order_id'])
            ->firstOrFail();

        IncidentReport::create([
            'order_id' => $order->id,
            'reported_by' => $user->id,
            'issue_type' => $data['issue_type'],
            'summary' => $data['summary'],
            'description' => $data['description'] ?? null,
        ]);

        return redirect()
            ->route('staff.incidents.index')
            ->with('success', 'Đã ghi nhận báo cáo sự cố cho đơn #' . $order->order_number . '.');
    }
}
