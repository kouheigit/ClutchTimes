{{-- トグルスイッチコンポーネント --}}
@props(['name' => 'toggle', 'value' => false, 'label' => null])

<label class="flex items-center">
    <div class="relative">
        <input type="checkbox" 
               name="{{ $name }}"
               value="1"
               {{ $value ? 'checked' : '' }}
               {{ $attributes->merge(['class' => 'sr-only']) }}>
        <div class="block bg-gray-600 w-14 h-8 rounded-full {{ $value ? 'bg-blue-600' : '' }}"></div>
        <div class="dot absolute left-1 top-1 bg-white w-6 h-6 rounded-full transition {{ $value ? 'transform translate-x-6' : '' }}"></div>
    </div>
    @if($label)
    <span class="ml-3 text-sm font-medium text-gray-700">{{ $label }}</span>
    @endif
</label>

