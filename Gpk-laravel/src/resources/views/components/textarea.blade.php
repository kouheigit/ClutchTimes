@props(['name', 'label' => '', 'value' => '', 'rows' => 3, 'required' => false, 'error' => null, 'placeholder' => ''])

<div class="mb-4">
    @if($label)
    <label for="{{ $name }}" class="block text-sm font-medium text-gray-700 mb-2">
        {{ $label }}
        @if($required)
        <span class="text-red-500">*</span>
        @endif
    </label>
    @endif
    
    <textarea name="{{ $name }}" 
              id="{{ $name }}"
              rows="{{ $rows }}"
              {{ $required ? 'required' : '' }}
              placeholder="{{ $placeholder }}"
              class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 {{ $error ? 'border-red-300' : '' }}">{{ old($name, $value) }}</textarea>
    
    @if($error)
    <p class="mt-1 text-sm text-red-600">{{ $error }}</p>
    @endif
</div>




















