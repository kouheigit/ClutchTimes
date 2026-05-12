<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>予約通知</title>
</head>
<body style="font-family: Arial, sans-serif; line-height: 1.6; color: #333;">
    <div style="max-width: 600px; margin: 0 auto; padding: 20px;">
        <h1 style="color: #dc2626; border-bottom: 2px solid #dc2626; padding-bottom: 10px;">
            @if($type == 'new')
            新規予約がありました
            @elseif($type == 'cancel')
            予約キャンセルがありました
            @else
            予約通知
            @endif
        </h1>
        
        <div style="background-color: #fef2f2; padding: 20px; margin: 20px 0; border-radius: 5px; border-left: 4px solid #dc2626;">
            <h2 style="margin-top: 0; color: #1f2937;">予約情報</h2>
            
            <p><strong>予約ID:</strong> {{ $reservation->id }}</p>
            <p><strong>予約者:</strong> {{ $user->name }} ({{ $user->email }})</p>
            <p><strong>チェックイン:</strong> {{ \Carbon\Carbon::parse($reservation->checkin_date)->format('Y年m月d日') }}</p>
            <p><strong>チェックアウト:</strong> {{ \Carbon\Carbon::parse($reservation->checkout_date)->format('Y年m月d日') }}</p>
            <p><strong>宿泊日数:</strong> {{ $reservation->days }}泊</p>
            <p><strong>宿泊人数:</strong> 大人{{ $reservation->adult }}名
                @if($reservation->child > 0) / 子供{{ $reservation->child }}名 @endif
                @if($reservation->dog > 0) / 犬{{ $reservation->dog }}頭 @endif
            </p>
            
            @if($reservation->hotel)
            <p><strong>ホテル:</strong> {{ $reservation->hotel->name }}</p>
            @endif
            
            <p><strong>ステータス:</strong> {{ \App\Consts\ReservationConst::STATUS_LIST[$reservation->status] ?? '不明' }}</p>
            <p><strong>決済方法:</strong> {{ $reservation->payment == 0 ? '現地払い' : 'クレジットカード' }}</p>
            
            @if($reservation->note)
            <p><strong>備考:</strong> {{ $reservation->note }}</p>
            @endif
        </div>
        
        @if($reservation->orders->count() > 0)
        <div style="background-color: #f3f4f6; padding: 20px; margin: 20px 0; border-radius: 5px;">
            <h2 style="margin-top: 0; color: #1f2937;">サービス注文</h2>
            @foreach($reservation->orders as $order)
            <p>{{ $order->service->title ?? 'サービス' }} × {{ $order->quantity }} - ¥{{ number_format($order->total_price) }}</p>
            @endforeach
        </div>
        @endif
        
        <p style="margin-top: 30px; padding-top: 20px; border-top: 1px solid #e5e7eb; color: #6b7280; font-size: 12px;">
            管理画面で詳細を確認してください。
        </p>
    </div>
</body>
</html>

