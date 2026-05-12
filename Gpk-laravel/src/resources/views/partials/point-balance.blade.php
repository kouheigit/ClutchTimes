@props(['balance', 'showDetails' => true])

<div class="bg-gradient-to-r from-blue-500 to-blue-600 overflow-hidden shadow-sm sm:rounded-lg">
    <div class="p-6 text-white">
        <h3 class="text-lg font-semibold mb-2">保有ポイント</h3>
        <p class="text-4xl font-bold">{{ number_format($balance->sum('point')) }} P</p>
        
        @if($showDetails && $balance->count() > 0)
        <div class="mt-4 space-y-2">
            @foreach($balance as $item)
            <div class="text-sm">
                <span>{{ $item->point }}P</span>
                <span class="ml-2 opacity-80">
                    ({{ \Carbon\Carbon::parse($item->to)->format('Y年m月末') }}まで有効)
                </span>
            </div>
            @endforeach
        </div>
        @endif
        
        <a href="{{ route('mypage.pointlog') }}" class="inline-block mt-4 text-sm underline hover:text-blue-100">
            ポイント履歴を見る →
        </a>
    </div>
</div>




















