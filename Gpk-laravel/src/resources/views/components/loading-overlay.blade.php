{{-- ローディングオーバーレイコンポーネント --}}
@props(['show' => false])

<div x-data="{ show: {{ $show ? 'true' : 'false' }} }" x-show="show" class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50" style="display: none;">
    <div class="bg-white rounded-lg p-6 flex flex-col items-center">
        <x-spinner size="lg" />
        <p class="mt-4 text-gray-600">{{ $text ?? '読み込み中...' }}</p>
    </div>
</div>

