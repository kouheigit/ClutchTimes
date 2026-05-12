{{-- アバターコンポーネント --}}
@props(['src' => null, 'alt' => '', 'size' => 'md'])

@php
$sizeClasses = match($size) {
    'sm' => 'w-8 h-8',
    'md' => 'w-10 h-10',
    'lg' => 'w-12 h-12',
    'xl' => 'w-16 h-16',
    default => 'w-10 h-10',
};
@endphp

@if($src)
<img src="{{ $src }}" alt="{{ $alt }}" {{ $attributes->merge(['class' => "{$sizeClasses} rounded-full object-cover"]) }}>
@else
<div {{ $attributes->merge(['class' => "{$sizeClasses} rounded-full bg-gray-300 flex items-center justify-center"]) }}>
    <span class="text-gray-600 font-medium">{{ substr($alt, 0, 1) }}</span>
</div>
@endif

