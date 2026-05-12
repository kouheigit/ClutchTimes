{{-- 月ピッカーコンポーネント --}}
@props(['name' => 'month', 'value' => ''])

<input type="month" 
       name="{{ $name }}"
       value="{{ $value }}"
       {{ $attributes->merge(['class' => 'block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm']) }}>

