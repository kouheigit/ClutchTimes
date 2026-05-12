{{-- サイドバーメニューパーシャル --}}
@props(['items' => []])

<nav class="space-y-1">
    @foreach($items as $item)
    <a href="{{ $item['url'] }}" 
       class="flex items-center px-4 py-2 text-sm font-medium rounded-md {{ request()->routeIs($item['route']) ? 'bg-blue-100 text-blue-900' : 'text-gray-700 hover:bg-gray-100' }}">
        @if(isset($item['icon']))
        <x-icon :name="$item['icon']" size="sm" class="mr-3" />
        @endif
        {{ $item['label'] }}
    </a>
    @endforeach
</nav>

