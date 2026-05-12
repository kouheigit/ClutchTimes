{{-- 範囲入力コンポーネント --}}
@props(['name' => 'range', 'value' => 50, 'min' => 0, 'max' => 100, 'step' => 1])

<div class="flex items-center space-x-4">
    <input type="range" 
           name="{{ $name }}"
           value="{{ $value }}"
           min="{{ $min }}"
           max="{{ $max }}"
           step="{{ $step }}"
           {{ $attributes->merge(['class' => 'flex-1']) }}
           oninput="this.nextElementSibling.value = this.value">
    <output class="text-sm font-medium text-gray-700">{{ $value }}</output>
</div>

