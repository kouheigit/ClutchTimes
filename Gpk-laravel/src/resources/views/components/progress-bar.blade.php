{{-- プログレスバーコンポーネント --}}
@props(['value' => 0, 'max' => 100, 'color' => 'blue'])

@php
$percentage = min(100, max(0, ($value / $max) * 100));
$colorClasses = match($color) {
    'blue' => 'bg-blue-600',
    'green' => 'bg-green-600',
    'yellow' => 'bg-yellow-600',
    'red' => 'bg-red-600',
    default => 'bg-blue-600',
};
@endphp

<div {{ $attributes->merge(['class' => 'w-full bg-gray-200 rounded-full h-2.5']) }}>
    <div class="{{ $colorClasses }} h-2.5 rounded-full transition-all duration-300" style="width: {{ $percentage }}%"></div>
</div>

