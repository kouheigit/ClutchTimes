{{-- リッチテキストエディタコンポーネント --}}
@props(['name' => 'content', 'value' => ''])

<div class="mt-1">
    <textarea name="{{ $name }}" 
              id="{{ $name }}"
              {{ $attributes->merge(['class' => 'block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm']) }}>{{ $value }}</textarea>
</div>
<script>
    // リッチテキストエディタの初期化（TinyMCEやCKEditorなど）
    // ここでは基本的なtextareaとして実装
</script>

