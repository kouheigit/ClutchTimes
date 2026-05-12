{{-- 警告メッセージパーシャル --}}
@props(['message'])

@if($message)
<div class="mb-4 bg-yellow-50 border border-yellow-200 text-yellow-800 px-4 py-3 rounded relative" role="alert">
    <span class="block sm:inline">{{ $message }}</span>
</div>
@endif

