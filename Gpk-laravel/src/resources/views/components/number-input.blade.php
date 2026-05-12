{{-- 数値入力コンポーネント --}}
@props(['name' => 'number', 'value' => '', 'min' => null, 'max' => null, 'step' => 1])

<input type="number" 
       name="{{ $name }}"
       value="{{ $value }}"
       @if($min !== null) min="{{ $min }}" @endif
       @if($max !== null) max="{{ $max }}" @endif
       step="{{ $step }}"
       {{ $attributes->merge(['class' => 'block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm']) }}>

