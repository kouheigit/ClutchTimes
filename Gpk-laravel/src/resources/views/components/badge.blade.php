{{-- バッジコンポーネント --}}
@props(['type' => 'default', 'size' => 'md'])

@php
$classes = match($type) {
    'success' => 'bg-green-100 text-green-800',
    'danger' => 'bg-red-100 text-red-800',
    'warning' => 'bg-yellow-100 text-yellow-800',
    'info' => 'bg-blue-100 text-blue-800',
    'default' => 'bg-gray-100 text-gray-800',
    default => 'bg-gray-100 text-gray-800',
};

$sizeClasses = match($size) {
    'sm' => 'text-xs px-2 py-1',
    'md' => 'text-sm px-3 py-1',
    'lg' => 'text-base px-4 py-2',
    default => 'text-sm px-3 py-1',
};
@endphp

<span {{ $attributes->merge(['class' => "inline-flex items-center rounded-full font-medium {$classes} {$sizeClasses}"]) }}>
    {{ $slot }}
</span>

