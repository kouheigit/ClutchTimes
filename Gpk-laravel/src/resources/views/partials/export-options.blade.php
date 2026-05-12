{{-- エクスポートオプションパーシャル --}}
@props(['formats' => ['csv', 'excel'], 'action' => null])

<div class="mb-4 flex items-center space-x-2">
    <span class="text-sm text-gray-700">エクスポート:</span>
    @foreach($formats as $format)
    <a href="{{ $action ?? request()->url() }}?export={{ $format }}" 
       class="px-3 py-1 text-sm bg-gray-100 text-gray-700 rounded hover:bg-gray-200">
        {{ strtoupper($format) }}
    </a>
    @endforeach
</div>

