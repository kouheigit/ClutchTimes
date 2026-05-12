{{-- 一括操作コンポーネント --}}
@props(['actions' => []])

<div x-data="{ selected: [] }" class="flex items-center space-x-2">
    <select x-model="selected" multiple class="hidden">
        {{ $slot }}
    </select>
    <div class="flex items-center space-x-2">
        @foreach($actions as $action)
        <button type="button" 
                @click="$dispatch('bulk-action', { action: '{{ $action['value'] }}', items: selected })"
                class="px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-md hover:bg-gray-50">
            {{ $action['label'] }}
        </button>
        @endforeach
    </div>
</div>

