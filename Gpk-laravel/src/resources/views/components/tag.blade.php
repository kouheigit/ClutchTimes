{{-- タグコンポーネント --}}
@props(['color' => 'gray', 'size' => 'md'])

@php
$colorClasses = match($color) {
    'blue' => 'bg-blue-100 text-blue-800',
    'green' => 'bg-green-100 text-green-800',
    'yellow' => 'bg-yellow-100 text-yellow-800',
    'red' => 'bg-red-100 text-red-800',
    'purple' => 'bg-purple-100 text-purple-800',
    'gray' => 'bg-gray-100 text-gray-800',
    default => 'bg-gray-100 text-gray-800',
};

$sizeClasses = match($size) {
    'sm' => 'text-xs px-2 py-0.5',
    'md' => 'text-sm px-2.5 py-1',
    'lg' => 'text-base px-3 py-1.5',
    default => 'text-sm px-2.5 py-1',
};
@endphp

<span {{ $attributes->merge(['class' => "inline-flex items-center rounded-full font-medium {$colorClasses} {$sizeClasses}"]) }}>
    {{ $slot }}
</span>

