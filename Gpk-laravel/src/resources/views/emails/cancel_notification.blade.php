<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>予約キャンセル通知</title>
</head>
<body>
    <h2>予約キャンセルのお知らせ</h2>
    
    <p>{{ $reservation->user->name }}様</p>
    
    <p>以下の予約がキャンセルされました。</p>
    
    <ul>
        <li>予約ID: {{ $reservation->id }}</li>
        <li>チェックイン: {{ \Carbon\Carbon::parse($reservation->checkin_date)->format('Y年m月d日') }}</li>
        <li>チェックアウト: {{ \Carbon\Carbon::parse($reservation->checkout_date)->format('Y年m月d日') }}</li>
        @if($reservation->hotel)
        <li>ホテル: {{ $reservation->hotel->name }}</li>
        @endif
    </ul>
    
    @if(isset($refund_amount) && $refund_amount > 0)
    <p><strong>返金額:</strong> ¥{{ number_format($refund_amount) }}</p>
    @endif
    
    <p>ご不明な点がございましたら、お気軽にお問い合わせください。</p>
</body>
</html>




















