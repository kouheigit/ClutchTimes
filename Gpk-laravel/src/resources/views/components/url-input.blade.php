{{-- URL入力コンポーネント --}}
@props(['name' => 'url', 'value' => '', 'placeholder' => 'https://example.com'])

<input type="url" 
       name="{{ $name }}"
       value="{{ $value }}"
       placeholder="{{ $placeholder }}"
       {{ $attributes->merge(['class' => 'block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm']) }}>

