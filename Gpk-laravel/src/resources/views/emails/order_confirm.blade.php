<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>サービス注文確定のお知らせ</title>
</head>
<body style="font-family: Arial, sans-serif; line-height: 1.6; color: #333;">
    <div style="max-width: 600px; margin: 0 auto; padding: 20px;">
        <h1 style="color: #2563eb; border-bottom: 2px solid #2563eb; padding-bottom: 10px;">
            サービス注文が確定しました
        </h1>
        
        <p>いつも空ノ庭をご利用いただき、ありがとうございます。</p>
        <p>以下の内容でサービス注文が確定いたしました。</p>
        
        <div style="background-color: #f3f4f6; padding: 20px; margin: 20px 0; border-radius: 5px;">
            <h2 style="margin-top: 0; color: #1f2937;">注文情報</h2>
            
            <p><strong>注文ID:</strong> {{ $order->id }}</p>
            <p><strong>サービス:</strong> {{ $order->service->title ?? 'サービス' }}</p>
            <p><strong>数量:</strong> {{ $order->quantity }}</p>
            <p><strong>単価:</strong> ¥{{ number_format($order->price) }}</p>
            <p><strong>合計金額:</strong> ¥{{ number_format($order->total_price) }}</p>
            
            @if($order->reservation)
            <p><strong>関連予約ID:</strong> {{ $order->reservation->id }}</p>
            @endif
            
            <p><strong>決済方法:</strong> {{ $order->payment == 0 ? '現地払い' : 'クレジットカード' }}</p>
        </div>
        
        <p>ご注文ありがとうございました。</p>
        
        <p style="margin-top: 30px; padding-top: 20px; border-top: 1px solid #e5e7eb; color: #6b7280; font-size: 12px;">
            このメールは自動送信されています。<br>
            ご不明な点がございましたら、お問い合わせください。
        </p>
    </div>
</body>
</html>

