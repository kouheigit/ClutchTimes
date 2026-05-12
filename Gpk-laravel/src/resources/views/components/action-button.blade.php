@props(['type' => 'button', 'variant' => 'primary', 'size' => 'md', 'href' => null])

@php
$variants = [
    'primary' => 'bg-blue-600 hover:bg-blue-700 text-white',
    'secondary' => 'bg-gray-600 hover:bg-gray-700 text-white',
    'success' => 'bg-green-600 hover:bg-green-700 text-white',
    'danger' => 'bg-red-600 hover:bg-red-700 text-white',
    'warning' => 'bg-yellow-600 hover:bg-yellow-700 text-white',
    'outline' => 'border border-gray-300 bg-white hover:bg-gray-50 text-gray-700',
];

$sizes = [
    'sm' => 'px-3 py-1.5 text-xs',
    'md' => 'px-4 py-2 text-sm',
    'lg' => 'px-6 py-3 text-base',
];

$variantClass = $variants[$variant] ?? $variants['primary'];
$sizeClass = $sizes[$size] ?? $sizes['md'];
$classes = "inline-flex items-center border border-transparent rounded-md font-semibold uppercase tracking-widest focus:outline-none focus:ring-2 focus:ring-offset-2 transition ease-in-out duration-150 {$variantClass} {$sizeClass}";
@endphp

@if($href)
<a href="{{ $href }}" class="{{ $classes }}">
    {{ $slot }}
</a>
@else
<button type="{{ $type }}" class="{{ $classes }}">
    {{ $slot }}
</button>
@endif




















