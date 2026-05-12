{{-- フォームアクションバーパーシャル --}}
@props(['submitLabel' => '保存', 'cancelUrl' => null, 'cancelLabel' => 'キャンセル'])

<div class="flex items-center justify-end space-x-3 mt-6 pt-6 border-t border-gray-200">
    @if($cancelUrl)
    <a href="{{ $cancelUrl }}" class="px-4 py-2 bg-gray-200 text-gray-700 rounded-md hover:bg-gray-300">
        {{ $cancelLabel }}
    </a>
    @endif
    <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700">
        {{ $submitLabel }}
    </button>
</div>

