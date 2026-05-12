@props(['reservation'])

<div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
    <h4 class="text-lg font-semibold mb-3">予約情報</h4>
    <div class="space-y-2 text-sm">
        <div class="flex justify-between">
            <span class="text-gray-600">予約ID:</span>
            <span class="font-medium">#{{ $reservation->id }}</span>
        </div>
        <div class="flex justify-between">
            <span class="text-gray-600">チェックイン:</span>
            <span class="font-medium">
                <x-date-display :date="$reservation->checkin_date" />
            </span>
        </div>
        <div class="flex justify-between">
            <span class="text-gray-600">チェックアウト:</span>
            <span class="font-medium">
                <x-date-display :date="$reservation->checkout_date" />
            </span>
        </div>
        <div class="flex justify-between">
            <span class="text-gray-600">宿泊日数:</span>
            <span class="font-medium">{{ $reservation->days }}泊</span>
        </div>
        @if($reservation->hotel)
        <div class="flex justify-between">
            <span class="text-gray-600">ホテル:</span>
            <span class="font-medium">{{ $reservation->hotel->name }}</span>
        </div>
        @endif
        <div class="flex justify-between">
            <span class="text-gray-600">宿泊人数:</span>
            <span class="font-medium">
                大人{{ $reservation->adult }}名
                @if($reservation->child > 0) / 子供{{ $reservation->child }}名 @endif
                @if($reservation->dog > 0) / 犬{{ $reservation->dog }}頭 @endif
            </span>
        </div>
        <div class="flex justify-between">
            <span class="text-gray-600">ステータス:</span>
            <x-status-badge :status="$reservation->status" type="reservation" />
        </div>
    </div>
</div>




















