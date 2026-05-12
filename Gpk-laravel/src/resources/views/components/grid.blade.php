{{-- グリッドコンポーネント --}}
@props(['cols' => 3, 'gap' => 6])

@php
$colClasses = match($cols) {
    1 => 'grid-cols-1',
    2 => 'grid-cols-1 md:grid-cols-2',
    3 => 'grid-cols-1 md:grid-cols-2 lg:grid-cols-3',
    4 => 'grid-cols-1 md:grid-cols-2 lg:grid-cols-4',
    default => 'grid-cols-1 md:grid-cols-2 lg:grid-cols-3',
};

$gapClasses = match($gap) {
    2 => 'gap-2',
    4 => 'gap-4',
    6 => 'gap-6',
    8 => 'gap-8',
    default => 'gap-6',
};
@endphp

<div {{ $attributes->merge(['class' => "grid {$colClasses} {$gapClasses}"]) }}>
    {{ $slot }}
</div>

