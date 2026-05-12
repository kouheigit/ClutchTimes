{{-- 検索可能セレクトコンポーネント --}}
@props(['name' => 'option', 'options' => [], 'selected' => null, 'placeholder' => '選択してください'])

<div x-data="{ open: false, search: '', selected: '{{ $selected ?? '' }}' }" class="relative">
    <button type="button" 
            @click="open = !open"
            class="relative w-full bg-white border border-gray-300 rounded-md shadow-sm pl-3 pr-10 py-2 text-left cursor-default focus:outline-none focus:ring-1 focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
        <span class="block truncate">{{ $selected ? collect($options)->firstWhere('value', $selected)['label'] ?? $placeholder : $placeholder }}</span>
        <span class="absolute inset-y-0 right-0 flex items-center pr-2 pointer-events-none">
            <svg class="h-5 w-5 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M10 3a1 1 0 01.707.293l3 3a1 1 0 01-1.414 1.414L10 5.414 7.707 7.707a1 1 0 01-1.414-1.414l3-3A1 1 0 0110 3zm-3.707 9.293a1 1 0 011.414 0L10 14.586l2.293-2.293a1 1 0 011.414 1.414l-3 3a1 1 0 01-1.414 0l-3-3a1 1 0 010-1.414z" clip-rule="evenodd" />
            </svg>
        </span>
    </button>
    <div x-show="open" 
         @click.away="open = false"
         x-transition
         class="absolute z-10 mt-1 w-full bg-white shadow-lg max-h-60 rounded-md py-1 text-base ring-1 ring-black ring-opacity-5 overflow-auto focus:outline-none sm:text-sm"
         style="display: none;">
        <input type="text" 
               x-model="search"
               placeholder="検索..."
               class="w-full px-3 py-2 border-b border-gray-200 focus:outline-none">
        @foreach($options as $option)
        <div x-show="!search || '{{ $option['label'] }}'.toLowerCase().includes(search.toLowerCase())"
             @click="selected = '{{ $option['value'] }}'; open = false"
             class="cursor-pointer select-none relative py-2 pl-3 pr-9 hover:bg-blue-50">
            <span class="block truncate">{{ $option['label'] }}</span>
        </div>
        @endforeach
    </div>
    <input type="hidden" name="{{ $name }}" x-model="selected">
</div>

