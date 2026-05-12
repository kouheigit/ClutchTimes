{{-- マルチセレクトコンポーネント --}}
@props(['name' => 'options', 'options' => [], 'selected' => []])

<select name="{{ $name }}[]" 
        multiple
        {{ $attributes->merge(['class' => 'block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm']) }}>
    @foreach($options as $option)
    <option value="{{ $option['value'] }}" {{ in_array($option['value'], $selected) ? 'selected' : '' }}>
        {{ $option['label'] }}
    </option>
    @endforeach
</select>

