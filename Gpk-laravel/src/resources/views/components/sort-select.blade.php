{{-- ソートセレクトコンポーネント --}}
@props(['options' => [], 'selected' => null, 'label' => '並び替え'])

<div class="flex items-center space-x-2">
    <label class="text-sm font-medium text-gray-700">{{ $label }}:</label>
    <select {{ $attributes->merge(['class' => 'block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm rounded-md']) }}>
        @foreach($options as $option)
        <option value="{{ $option['value'] }}" {{ ($selected ?? null) === $option['value'] ? 'selected' : '' }}>
            {{ $option['label'] }}
        </option>
        @endforeach
    </select>
</div>

