{{-- 検索バーパーシャル --}}
@props(['placeholder' => '検索...', 'action' => null, 'method' => 'GET'])

<form action="{{ $action ?? request()->url() }}" method="{{ $method }}" class="mb-4">
    <div class="flex gap-2">
        <x-search-input 
            name="search" 
            :value="request('search')" 
            :placeholder="$placeholder"
            class="flex-1" />
        <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700">
            検索
        </button>
        @if(request('search'))
        <a href="{{ request()->url() }}" class="px-4 py-2 bg-gray-200 text-gray-700 rounded-md hover:bg-gray-300">
            クリア
        </a>
        @endif
    </div>
</form>

