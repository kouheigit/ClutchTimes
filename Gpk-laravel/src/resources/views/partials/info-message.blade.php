{{-- 情報メッセージパーシャル --}}
@props(['message'])

@if($message)
<div class="mb-4 bg-blue-50 border border-blue-200 text-blue-800 px-4 py-3 rounded relative" role="alert">
    <span class="block sm:inline">{{ $message }}</span>
</div>
@endif

