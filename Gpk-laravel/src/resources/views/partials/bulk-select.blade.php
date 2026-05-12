{{-- 一括選択パーシャル --}}
@props(['name' => 'selected_items[]'])

<div class="mb-4">
    <label class="flex items-center">
        <input type="checkbox" 
               onchange="document.querySelectorAll('input[name=\'{{ $name }}\']').forEach(cb => cb.checked = this.checked)"
               class="rounded border-gray-300 text-blue-600 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
        <span class="ml-2 text-sm text-gray-700">全て選択</span>
    </label>
</div>

