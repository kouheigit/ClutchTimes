{{-- 電話番号入力コンポーネント --}}
@props(['name' => 'tel', 'value' => '', 'placeholder' => '090-1234-5678'])

<input type="tel" 
       name="{{ $name }}"
       value="{{ $value }}"
       placeholder="{{ $placeholder }}"
       {{ $attributes->merge(['class' => 'block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm']) }}>

