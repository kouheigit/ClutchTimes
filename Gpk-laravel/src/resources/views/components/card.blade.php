@props(['title' => '', 'footer' => null])

<div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
    @if($title)
    <div class="p-6 bg-white border-b border-gray-200">
        <h3 class="text-lg font-semibold">{{ $title }}</h3>
    </div>
    @endif
    
    <div class="p-6">
        {{ $slot }}
    </div>
    
    @if($footer)
    <div class="px-6 py-4 bg-gray-50 border-t border-gray-200">
        {{ $footer }}
    </div>
    @endif
</div>




















