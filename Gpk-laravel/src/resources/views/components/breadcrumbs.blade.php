{{-- パンくずリストコンポーネント --}}
@props(['items' => []])

<nav aria-label="Breadcrumb" class="mb-4">
    <ol class="flex items-center space-x-2 text-sm text-gray-500">
        <li>
            <a href="{{ route('top') }}" class="hover:text-gray-700">トップ</a>
        </li>
        @foreach($items as $item)
        <li>
            <span class="mx-2">/</span>
        </li>
        <li>
            @if(isset($item['url']))
                <a href="{{ $item['url'] }}" class="hover:text-gray-700">{{ $item['label'] }}</a>
            @else
                <span class="text-gray-900 font-medium">{{ $item['label'] }}</span>
            @endif
        </li>
        @endforeach
    </ol>
</nav>

