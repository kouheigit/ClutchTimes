{{-- カラーピッカーコンポーネント --}}
@props(['name' => 'color', 'value' => '#000000'])

<div class="flex items-center space-x-2">
    <input type="color" 
           name="{{ $name }}"
           value="{{ $value }}"
           {{ $attributes->merge(['class' => 'h-10 w-20 border border-gray-300 rounded cursor-pointer']) }}>
    <input type="text" 
           name="{{ $name }}_text"
           value="{{ $value }}"
           {{ $attributes->merge(['class' => 'block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm']) }}
           pattern="^#[0-9A-Fa-f]{6}$">
</div>

