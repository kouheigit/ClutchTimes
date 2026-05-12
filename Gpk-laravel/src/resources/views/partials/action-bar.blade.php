{{-- アクションバーパーシャル --}}
@props(['actions' => []])

<div class="mb-4 flex items-center justify-between">
    <div class="flex items-center space-x-2">
        {{ $slot }}
    </div>
    @if(count($actions) > 0)
    <div class="flex items-center space-x-2">
        @foreach($actions as $action)
        <a href="{{ $action['url'] }}" 
           class="px-4 py-2 {{ $action['variant'] ?? 'bg-blue-600' }} text-white rounded-md hover:opacity-90">
            {{ $action['label'] }}
        </a>
        @endforeach
    </div>
    @endif
</div>

