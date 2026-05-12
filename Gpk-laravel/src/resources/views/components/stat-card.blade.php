{{-- 統計カードコンポーネント --}}
@props(['title', 'value', 'change' => null, 'trend' => null])

<div {{ $attributes->merge(['class' => 'bg-white overflow-hidden shadow rounded-lg']) }}>
    <div class="p-5">
        <div class="flex items-center">
            <div class="flex-shrink-0">
                {{ $icon ?? '' }}
            </div>
            <div class="ml-5 w-0 flex-1">
                <dl>
                    <dt class="text-sm font-medium text-gray-500 truncate">{{ $title }}</dt>
                    <dd class="flex items-baseline">
                        <div class="text-2xl font-semibold text-gray-900">{{ $value }}</div>
                        @if($change)
                        <div class="ml-2 flex items-baseline text-sm font-semibold {{ $trend === 'up' ? 'text-green-600' : ($trend === 'down' ? 'text-red-600' : 'text-gray-600') }}">
                            {{ $change }}
                        </div>
                        @endif
                    </dd>
                </dl>
            </div>
        </div>
    </div>
</div>

