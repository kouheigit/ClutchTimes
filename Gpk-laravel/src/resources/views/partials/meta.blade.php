{{-- メタ情報パーシャル --}}
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<meta name="csrf-token" content="{{ csrf_token() }}">
<meta name="description" content="{{ $meta_description ?? '空ノ庭（軽井沢）会員制宿泊予約システム' }}">
<meta name="keywords" content="{{ $meta_keywords ?? '軽井沢,宿泊,予約,会員制' }}">
<title>{{ $meta_title ?? config('app.name', 'Laravel') }}</title>

