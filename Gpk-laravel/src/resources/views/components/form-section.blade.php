@props(['title' => '', 'description' => ''])

<div class="mb-6">
    @if($title)
    <h3 class="text-lg font-semibold mb-2">{{ $title }}</h3>
    @endif
    
    @if($description)
    <p class="text-sm text-gray-600 mb-4">{{ $description }}</p>
    @endif
    
    <div class="space-y-4">
        {{ $slot }}
    </div>
</div>




















