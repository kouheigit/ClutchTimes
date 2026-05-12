@props(['name', 'label' => '', 'value' => 1, 'checked' => false, 'error' => null])

<div class="mb-4">
    <div class="flex items-center">
        <input type="checkbox" 
               name="{{ $name }}" 
               id="{{ $name }}"
               value="{{ $value }}"
               {{ $checked || old($name) ? 'checked' : '' }}
               class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300 rounded {{ $error ? 'border-red-300' : '' }}">
        
        @if($label)
        <label for="{{ $name }}" class="ml-2 block text-sm text-gray-900">
            {{ $label }}
        </label>
        @endif
    </div>
    
    @if($error)
    <p class="mt-1 text-sm text-red-600">{{ $error }}</p>
    @endif
</div>




















