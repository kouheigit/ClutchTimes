<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            予約一覧
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @if(session('success'))
            <x-alert type="success" dismissible>
                {{ session('success') }}
            </x-alert>
            @endif

            <!-- フィルター -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6">
                    <form method="GET" action="{{ route('mypage.reservations') }}" class="flex items-center space-x-4">
                        <select name="status" class="rounded-md border-gray-300 shadow-sm">
                            <option value="">全て</option>
                            <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>有効な予約</option>
                            <option value="past" {{ request('status') == 'past' ? 'selected' : '' }}>過去の予約</option>
                            <option value="cancelled" {{ request('status') == 'cancelled' ? 'selected' : '' }}>キャンセル済み</option>
                        </select>
                        <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">
                            フィルター
                        </button>
                    </form>
                </div>
            </div>

            <!-- 予約一覧 -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    @if($reservations->count() > 0)
                    <div class="space-y-4">
                        @foreach($reservations as $reservation)
                        <div class="border rounded-lg p-4 hover:shadow-md transition">
                            <div class="flex justify-between items-start">
                                <div class="flex-1">
                                    <p class="font-medium text-lg">
                                        <x-date-display :date="$reservation->checkin_date" />
                                        ～
                                        <x-date-display :date="$reservation->checkout_date" />
                                        ({{ $reservation->days }}泊)
                                    </p>
                                    @if($reservation->hotel)
                                    <p class="text-sm text-gray-600 mt-1">
                                        {{ $reservation->hotel->name }}
                                    </p>
                                    @endif
                                    <p class="text-sm text-gray-600 mt-1">
                                        大人{{ $reservation->adult }}名
                                        @if($reservation->child > 0) / 子供{{ $reservation->child }}名 @endif
                                        @if($reservation->dog > 0) / 犬{{ $reservation->dog }}頭 @endif
                                    </p>
                                    <p class="mt-2">
                                        <x-status-badge :status="$reservation->status" type="reservation" />
                                    </p>
                                </div>
                                <div>
                                    <a href="{{ route('reservation.show', $reservation) }}"
                                       class="inline-flex items-center px-4 py-2 bg-gray-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700">
                                        詳細を見る
                                    </a>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                    
                    <div class="mt-6">
                        {{ $reservations->links('components.pagination') }}
                    </div>
                    @else
                    <x-empty-state 
                        title="予約がありません"
                        description="まだ予約がありません。"
                        :action="route('reservation.index')"
                        actionLabel="予約する" />
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>




















