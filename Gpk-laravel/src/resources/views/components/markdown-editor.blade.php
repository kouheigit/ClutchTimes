{{-- マークダウンエディタコンポーネント --}}
@props(['name' => 'content', 'value' => ''])

<div class="mt-1 border border-gray-300 rounded-md">
    <div class="flex border-b border-gray-300">
        <button type="button" class="px-3 py-2 text-sm font-medium text-gray-700 border-r border-gray-300 hover:bg-gray-50">編集</button>
        <button type="button" class="px-3 py-2 text-sm font-medium text-gray-500 hover:bg-gray-50">プレビュー</button>
    </div>
    <textarea name="{{ $name }}" 
              {{ $attributes->merge(['class' => 'block w-full rounded-md border-0 focus:ring-0 sm:text-sm']) }} 
              rows="10">{{ $value }}</textarea>
</div>

