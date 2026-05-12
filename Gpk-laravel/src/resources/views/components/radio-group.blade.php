{{-- ラジオグループコンポーネント --}}
@props(['options' => [], 'name' => 'option', 'selected' => null])

<div class="space-y-2">
    @foreach($options as $option)
    <label class="flex items-center">
        <input type="radio" 
               name="{{ $name }}" 
               value="{{ $option['value'] }}"
               {{ ($selected ?? null) === $option['value'] ? 'checked' : '' }}
               class="border-gray-300 text-blue-600 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
        <span class="ml-2 text-sm text-gray-700">{{ $option['label'] }}</span>
    </label>
    @endforeach
</div>

