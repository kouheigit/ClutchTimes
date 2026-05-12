{{-- スラッグ入力コンポーネント --}}
@props(['name' => 'slug', 'value' => '', 'source' => 'title'])

<div class="mt-1 flex rounded-md shadow-sm">
    <span class="inline-flex items-center px-3 rounded-l-md border border-r-0 border-gray-300 bg-gray-50 text-gray-500 text-sm">
        /slug/
    </span>
    <input type="text" 
           name="{{ $name }}"
           value="{{ $value }}"
           {{ $attributes->merge(['class' => 'flex-1 block w-full rounded-none rounded-r-md border-gray-300 focus:border-blue-500 focus:ring-blue-500 sm:text-sm']) }}
           data-source="{{ $source }}">
</div>
<script>
    // スラッグの自動生成（JavaScriptで実装）
</script>

