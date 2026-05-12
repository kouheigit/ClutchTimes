@props(['name', 'label' => '', 'options' => [], 'value' => '', 'required' => false, 'error' => null])

<div class="mb-4">
    @if($label)
    <label for="{{ $name }}" class="block text-sm font-medium text-gray-700 mb-2">
        {{ $label }}
        @if($required)
        <span class="text-red-500">*</span>
        @endif
    </label>
    @endif
    
    <select name="{{ $name }}" 
            id="{{ $name }}"
            {{ $required ? 'required' : '' }}
            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 {{ $error ? 'border-red-300' : '' }}">
        @foreach($options as $optionValue => $optionLabel)
        <option value="{{ $optionValue }}" {{ $value == $optionValue ? 'selected' : '' }}>
            {{ $optionLabel }}
        </option>
        @endforeach
    </select>
    
    @if($error)
    <p class="mt-1 text-sm text-red-600">{{ $error }}</p>
    @endif
</div>




















