@props(['href' => '#', 'variant' => 'primary', 'size' => 'default'])

@php
    $classes = 'btn ';
    $classes .= match($variant) {
        'primary' => 'btn-primary',
        'secondary' => 'btn-secondary',
        'outline' => 'btn-outline',
        'ghost' => 'btn-ghost',
        default => 'btn-primary'
    };
    $classes .= ' ' . match($size) {
        'sm' => 'btn-sm',
        'lg' => 'btn-lg',
        default => ''
    };
@endphp

<a href="{{ $href }}" {{ $attributes->merge(['class' => $classes]) }}>
    {{ $slot }}
</a>
