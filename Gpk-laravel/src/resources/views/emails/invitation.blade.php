<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ご招待のお知らせ</title>
</head>
<body style="font-family: Arial, sans-serif; line-height: 1.6; color: #333;">
    <div style="max-width: 600px; margin: 0 auto; padding: 20px;">
        <h1 style="color: #2563eb; border-bottom: 2px solid #2563eb; padding-bottom: 10px;">
            ご招待のお知らせ
        </h1>
        
        <p>{{ $name }} 様</p>
        <p>いつも空ノ庭をご利用いただき、ありがとうございます。</p>
        <p>この度、空ノ庭へのご招待をさせていただきます。</p>
        
        @if($reservation)
        <div style="background-color: #f3f4f6; padding: 20px; margin: 20px 0; border-radius: 5px;">
            <h2 style="margin-top: 0; color: #1f2937;">予約情報</h2>
            
            @if($reservation->hotel)
            <p><strong>施設:</strong> {{ $reservation->hotel->name }}</p>
            @endif
            
            <p><strong>チェックイン:</strong> {{ \Carbon\Carbon::parse($reservation->checkin_date)->format('Y年m月d日') }}</p>
            <p><strong>チェックアウト:</strong> {{ \Carbon\Carbon::parse($reservation->checkout_date)->format('Y年m月d日') }}</p>
            <p><strong>宿泊日数:</strong> {{ $reservation->days }}泊</p>
        </div>
        @endif
        
        <div style="background-color: #dbeafe; padding: 20px; margin: 20px 0; border-radius: 5px; border-left: 4px solid #2563eb;">
            <h3 style="margin-top: 0; color: #1e40af;">会員登録について</h3>
            <p>以下のURLから会員登録を行ってください。</p>
            <p style="margin: 15px 0;">
                <a href="{{ $url }}" style="display: inline-block; padding: 10px 20px; background-color: #2563eb; color: white; text-decoration: none; border-radius: 5px;">
                    会員登録を行う
                </a>
            </p>
            <p style="font-size: 12px; color: #6b7280; word-break: break-all;">
                URL: {{ $url }}
            </p>
        </div>
        
        <p>ご不明な点がございましたら、お気軽にお問い合わせください。</p>
        
        <p style="margin-top: 30px; padding-top: 20px; border-top: 1px solid #e5e7eb; color: #6b7280; font-size: 12px;">
            GLAMDAY STYLE TEITAKU 空ノ庭<br>
            Email: info@soranoniwa.jp
        </p>
    </div>
</body>
</html>

