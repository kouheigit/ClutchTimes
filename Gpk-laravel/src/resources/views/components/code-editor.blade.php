{{-- コードエディタコンポーネント --}}
@props(['name' => 'code', 'value' => '', 'language' => 'php'])

<div class="mt-1">
    <textarea name="{{ $name }}" 
              {{ $attributes->merge(['class' => 'block w-full font-mono text-sm rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500']) }} 
              rows="15">{{ $value }}</textarea>
</div>

