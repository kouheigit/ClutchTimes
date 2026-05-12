{{-- 最近のアクティビティパーシャル --}}
@props(['activities' => []])

<div class="bg-white shadow rounded-lg">
    <div class="px-4 py-5 sm:p-6">
        <h3 class="text-lg font-medium text-gray-900 mb-4">最近のアクティビティ</h3>
        @if(count($activities) > 0)
        <x-timeline>
            @foreach($activities as $activity)
            <x-timeline-item :date="$activity['date'] ?? null" :icon="$activity['icon'] ?? null">
                {{ $activity['description'] }}
            </x-timeline-item>
            @endforeach
        </x-timeline>
        @else
        <p class="text-gray-500 text-sm">アクティビティがありません</p>
        @endif
    </div>
</div>

