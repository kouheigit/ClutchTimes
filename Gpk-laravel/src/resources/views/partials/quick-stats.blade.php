{{-- クイック統計パーシャル --}}
@props(['stats' => []])

<div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
    @foreach($stats as $stat)
    <div class="bg-white overflow-hidden shadow rounded-lg">
        <div class="p-5">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    @if(isset($stat['icon']))
                    <x-icon :name="$stat['icon']" size="lg" class="text-gray-400" />
                    @endif
                </div>
                <div class="ml-5 w-0 flex-1">
                    <dl>
                        <dt class="text-sm font-medium text-gray-500 truncate">{{ $stat['label'] }}</dt>
                        <dd class="text-lg font-semibold text-gray-900">{{ $stat['value'] }}</dd>
                    </dl>
                </div>
            </div>
        </div>
    </div>
    @endforeach
</div>

