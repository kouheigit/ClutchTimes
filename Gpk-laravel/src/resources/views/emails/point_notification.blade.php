<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>ポイント通知</title>
</head>
<body>
    <h2>ポイント{{ $type === 'add' ? '付与' : '利用' }}のお知らせ</h2>
    
    <p>{{ $user->name }}様</p>
    
    <p>
        @if($type === 'add')
        ご予約ありがとうございました。<br>
        {{ $point }}ポイントを付与いたしました。
        @else
        {{ $point }}ポイントを利用いたしました。
        @endif
    </p>
    
    @if(isset($reason))
    <p><strong>理由:</strong> {{ $reason }}</p>
    @endif
    
    @if(isset($expiry))
    <p><strong>有効期限:</strong> {{ \Carbon\Carbon::parse($expiry)->format('Y年m月d日') }}</p>
    @endif
    
    <p>現在のポイント残高: {{ $balance }}ポイント</p>
</body>
</html>




















