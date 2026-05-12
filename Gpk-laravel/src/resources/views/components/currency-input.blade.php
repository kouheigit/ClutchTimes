{{-- 通貨入力コンポーネント --}}
@props(['name' => 'amount', 'value' => '', 'currency' => 'JPY'])

<div class="relative rounded-md shadow-sm">
    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
        <span class="text-gray-500 sm:text-sm">{{ $currency === 'JPY' ? '¥' : '$' }}</span>
    </div>
    <input type="text" 
           name="{{ $name }}"
           value="{{ $value }}"
           {{ $attributes->merge(['class' => 'block w-full pl-7 pr-3 py-2 border border-gray-300 rounded-md leading-5 bg-white placeholder-gray-500 focus:outline-none focus:placeholder-gray-400 focus:ring-1 focus:ring-blue-500 focus:border-blue-500 sm:text-sm']) }}
           placeholder="0">
</div>

