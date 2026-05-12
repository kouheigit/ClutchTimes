{{-- ステータスフィルターコンポーネント --}}
@props(['statuses' => [], 'selected' => null])

<div class="flex flex-wrap gap-2">
    <a href="{{ request()->url() }}" 
       class="px-3 py-1 rounded-full text-sm {{ $selected === null ? 'bg-blue-600 text-white' : 'bg-gray-200 text-gray-700 hover:bg-gray-300' }}">
        全て
    </a>
    @foreach($statuses as $status)
    <a href="{{ request()->fullUrlWithQuery(['status' => $status['value']]) }}" 
       class="px-3 py-1 rounded-full text-sm {{ $selected === $status['value'] ? 'bg-blue-600 text-white' : 'bg-gray-200 text-gray-700 hover:bg-gray-300' }}">
        {{ $status['label'] }}
    </a>
    @endforeach
</div>

