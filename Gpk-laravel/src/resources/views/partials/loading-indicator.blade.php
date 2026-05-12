{{-- ローディングインジケーターパーシャル --}}
@props(['text' => '読み込み中...'])

<div class="flex items-center justify-center py-8">
    <div class="flex flex-col items-center">
        <x-spinner size="lg" />
        <p class="mt-4 text-gray-600">{{ $text }}</p>
    </div>
</div>

