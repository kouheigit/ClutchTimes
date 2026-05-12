{{-- 見出しコンポーネント --}}
@props(['level' => 1, 'size' => null])

@php
$tag = 'h' . $level;
$sizeClasses = $size ?? match($level) {
    1 => 'text-3xl font-bold',
    2 => 'text-2xl font-semibold',
    3 => 'text-xl font-semibold',
    4 => 'text-lg font-medium',
    default => 'text-xl font-semibold',
};
@endphp

<{{ $tag }} {{ $attributes->merge(['class' => "{$sizeClasses} text-gray-900"]) }}>
    {{ $slot }}
</{{ $tag }}>

