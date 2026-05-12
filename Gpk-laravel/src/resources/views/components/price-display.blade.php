@props(['price', 'unit' => '円'])

<span class="text-2xl font-bold text-blue-600">
    ¥{{ number_format($price) }}
</span>
@if($unit !== '円')
<span class="text-sm text-gray-500">/ {{ $unit }}</span>
@endif




















