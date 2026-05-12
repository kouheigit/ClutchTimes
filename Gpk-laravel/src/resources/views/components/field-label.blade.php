{{-- フィールドラベルコンポーネント --}}
@props(['required' => false, 'for' => null])

<label {{ $attributes->merge(['class' => 'block text-sm font-medium text-gray-700']) }} @if($for) for="{{ $for }}" @endif>
    {{ $slot }}
    @if($required)
    <span class="text-red-500">*</span>
    @endif
</label>

