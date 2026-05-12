{{-- 単位付き入力コンポーネント --}}
@props(['name' => 'value', 'value' => '', 'unit' => ''])

<div class="flex rounded-md shadow-sm">
    <input type="text" 
           name="{{ $name }}"
           value="{{ $value }}"
           {{ $attributes->merge(['class' => 'flex-1 block w-full rounded-l-md border-gray-300 focus:border-blue-500 focus:ring-blue-500 sm:text-sm']) }}>
    <span class="inline-flex items-center px-3 rounded-r-md border border-l-0 border-gray-300 bg-gray-50 text-gray-500 text-sm">
        {{ $unit }}
    </span>
</div>

