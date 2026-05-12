{{-- 最近のアイテムパーシャル --}}
@props(['items' => [], 'title' => '最近のアイテム'])

<div class="bg-white shadow rounded-lg">
    <div class="px-4 py-5 sm:p-6">
        <h3 class="text-lg font-medium text-gray-900 mb-4">{{ $title }}</h3>
        @if(count($items) > 0)
        <ul class="divide-y divide-gray-200">
            @foreach($items as $item)
            <li class="py-3">
                <div class="flex items-center justify-between">
                    <div class="flex-1">
                        <p class="text-sm font-medium text-gray-900">{{ $item['title'] }}</p>
                        @if(isset($item['subtitle']))
                        <p class="text-sm text-gray-500">{{ $item['subtitle'] }}</p>
                        @endif
                    </div>
                    @if(isset($item['url']))
                    <a href="{{ $item['url'] }}" class="ml-4 text-sm text-blue-600 hover:text-blue-900">
                        詳細 →
                    </a>
                    @endif
                </div>
            </li>
            @endforeach
        </ul>
        @else
        <p class="text-gray-500 text-sm">アイテムがありません</p>
        @endif
    </div>
</div>

