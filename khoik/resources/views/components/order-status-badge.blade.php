@props(['status'])

@php
    $classes = match($status) {
        'pending' => 'badge-pending',
        'confirmed' => 'badge-confirmed',
        'picked_up' => 'badge-picked_up',
        'in_transit' => 'badge-in_transit',
        'out_delivery' => 'badge-out_delivery',
        'delivered' => 'badge-delivered',
        'cancelled' => 'badge-cancelled',
        'returned' => 'badge-returned',
        default => 'badge-pending'
    };
    
    $labels = [
        'pending' => 'Chờ xử lý',
        'confirmed' => 'Đã xác nhận',
        'picked_up' => 'Đã lấy hàng',
        'in_transit' => 'Đang vận chuyển',
        'out_delivery' => 'Đang giao hàng',
        'delivered' => 'Đã giao hàng',
        'cancelled' => 'Đã hủy',
        'returned' => 'Hoàn trả',
    ];
@endphp

<span {{ $attributes->merge(['class' => "badge $classes"]) }}>
    {{ $labels[$status] ?? $status }}
</span>
