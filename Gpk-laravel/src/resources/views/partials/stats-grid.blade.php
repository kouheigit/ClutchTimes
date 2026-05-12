{{-- 統計グリッドパーシャル --}}
@props(['stats' => []])

<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
    @foreach($stats as $stat)
    <x-stat-card 
        :title="$stat['title']" 
        :value="$stat['value']" 
        :change="$stat['change'] ?? null"
        :trend="$stat['trend'] ?? null">
        @if(isset($stat['icon']))
        <x-slot name="icon">
            <x-icon :name="$stat['icon']" size="lg" class="text-gray-400" />
        </x-slot>
        @endif
    </x-stat-card>
    @endforeach
</div>

