{{-- 段落コンポーネント --}}
@props(['size' => 'base', 'color' => 'gray'])

@php
$sizeClasses = match($size) {
    'sm' => 'text-sm',
    'base' => 'text-base',
    'lg' => 'text-lg',
    default => 'text-base',
};

$colorClasses = match($color) {
    'gray' => 'text-gray-600',
    'dark' => 'text-gray-900',
    'light' => 'text-gray-500',
    default => 'text-gray-600',
};
@endphp

<p {{ $attributes->merge(['class' => "{$sizeClasses} {$colorClasses}"]) }}>
    {{ $slot }}
</p>

