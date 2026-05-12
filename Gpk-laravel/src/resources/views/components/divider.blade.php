{{-- 区切り線コンポーネント --}}
@props(['spacing' => 'md'])

@php
$spacingClasses = match($spacing) {
    'sm' => 'my-4',
    'md' => 'my-6',
    'lg' => 'my-8',
    default => 'my-6',
};
@endphp

<hr {{ $attributes->merge(['class' => "border-gray-200 {$spacingClasses}"]) }}>

