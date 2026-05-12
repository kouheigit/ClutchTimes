# Phase 10-12: フロント画面からメール機能まで

---

## Phase 10: フロント画面実装（2-3週間）

### 目標
全166個のビューファイルを実装

### Step 10-1: レイアウト・共通コンポーネント（Day 1-2）

#### 1. アプリケーションレイアウト
```blade
{{-- resources/views/layouts/app.blade.php --}}
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Noto+Sans+JP:wght@300;400;500;700&display=swap" rel="stylesheet">

    <!-- Styles -->
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    
    @stack('styles')
</head>
<body class="font-sans antialiased">
    <div class="min-h-screen bg-gray-100">
        @include('layouts.navigation')

        <!-- Page Heading -->
        @if (isset($header))
            <header class="bg-white shadow">
                <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                    {{ $header }}
                </div>
            </header>
        @endif

        <!-- Page Content -->
        <main>
            @if (session('success'))
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mt-4">
                    <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative">
                        {{ session('success') }}
                    </div>
                </div>
            @endif
            
            @if ($errors->any())
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mt-4">
                    <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative">
                        <ul class="list-disc list-inside">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            @endif
            
            {{ $slot }}
        </main>
        
        @if (isset($footer))
            <footer>
                {{ $footer }}
            </footer>
        @endif
    </div>
    
    <script src="{{ asset('js/app.js') }}"></script>
    @stack('scripts')
</body>
</html>
```

#### 2. ナビゲーション
```blade
{{-- resources/views/layouts/navigation.blade.php --}}
<nav x-data="{ open: false }" class="bg-white border-b border-gray-100">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            <div class="flex">
                <!-- Logo -->
                <div class="shrink-0 flex items-center">
                    <a href="{{ route('top') }}">
                        <span class="text-xl font-bold">空ノ庭</span>
                    </a>
                </div>

                <!-- Navigation Links -->
                <div class="hidden space-x-8 sm:-my-px sm:ml-10 sm:flex">
                    <x-nav-link :href="route('top')" :active="request()->routeIs('top')">
                        トップ
                    </x-nav-link>
                    <x-nav-link :href="route('mypage.index')" :active="request()->routeIs('mypage.*')">
                        マイページ
                    </x-nav-link>
                    <x-nav-link :href="route('reservation.index')" :active="request()->routeIs('reservation.*')">
                        予約
                    </x-nav-link>
                    <x-nav-link :href="route('services.index')" :active="request()->routeIs('services.*')">
                        サービス
                    </x-nav-link>
                    <x-nav-link :href="route('news.index')" :active="request()->routeIs('news.*')">
                        お知らせ
                    </x-nav-link>
                </div>
            </div>

            <!-- Settings Dropdown -->
            <div class="hidden sm:flex sm:items-center sm:ml-6">
                <x-dropdown align="right" width="48">
                    <x-slot name="trigger">
                        <button class="flex items-center text-sm font-medium text-gray-500 hover:text-gray-700">
                            <div>{{ Auth::user()->name }}</div>
                            <div class="ml-1">
                                <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                </svg>
                            </div>
                        </button>
                    </x-slot>

                    <x-slot name="content">
                        <x-dropdown-link :href="route('mypage.index')">
                            マイページ
                        </x-dropdown-link>
                        <x-dropdown-link :href="route('mypage.edit')">
                            プロフィール編集
                        </x-dropdown-link>
                        <x-dropdown-link :href="route('mypage.pointlog')">
                            ポイント履歴
                        </x-dropdown-link>
                        
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <x-dropdown-link :href="route('logout')"
                                    onclick="event.preventDefault(); this.closest('form').submit();">
                                ログアウト
                            </x-dropdown-link>
                        </form>
                    </x-slot>
                </x-dropdown>
            </div>

            <!-- Hamburger -->
            <div class="-mr-2 flex items-center sm:hidden">
                <button @click="open = ! open" class="inline-flex items-center justify-center p-2 rounded-md text-gray-400 hover:text-gray-500 hover:bg-gray-100">
                    <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path :class="{'hidden': open, 'inline-flex': ! open }" class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        <path :class="{'hidden': ! open, 'inline-flex': open }" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <!-- Responsive Navigation Menu -->
    <div :class="{'block': open, 'hidden': ! open}" class="hidden sm:hidden">
        <div class="pt-2 pb-3 space-y-1">
            <x-responsive-nav-link :href="route('top')" :active="request()->routeIs('top')">
                トップ
            </x-responsive-nav-link>
            <x-responsive-nav-link :href="route('mypage.index')" :active="request()->routeIs('mypage.*')">
                マイページ
            </x-responsive-nav-link>
            <x-responsive-nav-link :href="route('reservation.index')" :active="request()->routeIs('reservation.*')">
                予約
            </x-responsive-nav-link>
            <x-responsive-nav-link :href="route('services.index')" :active="request()->routeIs('services.*')">
                サービス
            </x-responsive-nav-link>
        </div>

        <!-- Responsive Settings Options -->
        <div class="pt-4 pb-1 border-t border-gray-200">
            <div class="px-4">
                <div class="font-medium text-base text-gray-800">{{ Auth::user()->name }}</div>
                <div class="font-medium text-sm text-gray-500">{{ Auth::user()->email }}</div>
            </div>

            <div class="mt-3 space-y-1">
                <x-responsive-nav-link :href="route('mypage.edit')">
                    プロフィール編集
                </x-responsive-nav-link>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <x-responsive-nav-link :href="route('logout')"
                            onclick="event.preventDefault(); this.closest('form').submit();">
                        ログアウト
                    </x-responsive-nav-link>
                </form>
            </div>
        </div>
    </div>
</nav>
```

### Step 10-2: 予約画面実装（Day 3-7）

#### 予約一覧画面
```blade
{{-- resources/views/reservation/index.blade.php --}}
<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            予約管理
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- FIXDAY -->
            @if($calendars->count() > 0)
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6 bg-white border-b border-gray-200">
                    <h3 class="text-lg font-semibold mb-4">FIXDAY（固定日予約）</h3>
                    
                    <div class="space-y-4">
                        @foreach($calendars as $calendar)
                        <div class="border rounded-lg p-4 hover:shadow-md transition">
                            <div class="flex justify-between items-center">
                                <div>
                                    <p class="text-lg font-medium">
                                        {{ \Carbon\Carbon::parse($calendar->start_date)->format('Y年m月d日(D)') }}
                                        ～
                                        {{ \Carbon\Carbon::parse($calendar->end_date)->format('m月d日(D)') }}
                                    </p>
                                    <p class="text-sm text-gray-600 mt-1">
                                        {{ $calendar->hotel->name ?? '' }}
                                    </p>
                                    <p class="mt-2">
                                        @if($calendar->status == 1)
                                            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-green-100 text-green-800">
                                                予約可能
                                            </span>
                                        @elseif($calendar->status == 2)
                                            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-yellow-100 text-yellow-800">
                                                予約中
                                            </span>
                                        @elseif($calendar->status == 3)
                                            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-blue-100 text-blue-800">
                                                予約確定
                                            </span>
                                        @else
                                            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-gray-100 text-gray-800">
                                                {{ $calendar->status }}
                                            </span>
                                        @endif
                                    </p>
                                </div>
                                <div>
                                    @if($calendar->status == 1)
                                        <a href="{{ route('reservation.create', ['calendar_id' => $calendar->id]) }}" 
                                           class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700">
                                            予約する
                                        </a>
                                    @elseif($calendar->status == 2 || $calendar->status == 3)
                                        <a href="{{ route('mypage.reservation') }}" 
                                           class="inline-flex items-center px-4 py-2 bg-gray-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700">
                                            詳細を見る
                                        </a>
                                    @endif
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
            @endif

            <!-- FREEDAY -->
            @if($freedays->count() > 0)
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <h3 class="text-lg font-semibold mb-4">FREEDAY（自由日予約）</h3>
                    
                    <div class="space-y-4">
                        @foreach($freedays as $freeday)
                        <div class="border rounded-lg p-4 hover:shadow-md transition">
                            <div class="flex justify-between items-center">
                                <div>
                                    <p class="text-lg font-medium">
                                        利用可能: <span class="text-blue-600">{{ $freeday->freedays }}泊</span>
                                    </p>
                                    <p class="text-sm text-gray-600 mt-1">
                                        有効期限: {{ \Carbon\Carbon::parse($freeday->end_date)->format('Y年m月末日') }}まで
                                    </p>
                                    <p class="text-xs text-gray-500 mt-1">
                                        利用開始: {{ \Carbon\Carbon::parse($freeday->start_date)->format('Y年m月') }}から
                                    </p>
                                </div>
                                <div>
                                    @if(\Carbon\Carbon::parse($freeday->start_date)->firstOfMonth()->subMonths(18) <= \Carbon\Carbon::now())
                                        <a href="{{ route('reservation.index', ['fr' => $freeday->id, 'd' => $freeday->start_date]) }}" 
                                           class="inline-flex items-center px-4 py-2 bg-green-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-green-700">
                                            予約する
                                        </a>
                                    @else
                                        <button disabled class="inline-flex items-center px-4 py-2 bg-gray-300 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest cursor-not-allowed">
                                            利用不可
                                        </button>
                                    @endif
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
            @endif
        </div>
    </div>
</x-app-layout>
```

#### 予約作成画面
```blade
{{-- resources/views/reservation/create.blade.php --}}
<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            予約作成
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <form method="POST" action="{{ route('reservation.service') }}">
                        @csrf
                        
                        <input type="hidden" name="calendar_id" value="{{ $calendar->id }}">
                        
                        <!-- 日程表示 -->
                        <div class="mb-6">
                            <h3 class="text-lg font-semibold mb-2">予約日程</h3>
                            <div class="bg-blue-50 p-4 rounded">
                                <p class="text-lg">
                                    <span class="font-medium">チェックイン:</span>
                                    {{ \Carbon\Carbon::parse($calendar->start_date)->format('Y年m月d日(D)') }}
                                </p>
                                <p class="text-lg mt-1">
                                    <span class="font-medium">チェックアウト:</span>
                                    {{ \Carbon\Carbon::parse($calendar->end_date)->addDay()->format('Y年m月d日(D)') }}
                                </p>
                                <p class="text-sm text-gray-600 mt-2">
                                    宿泊日数: {{ \Carbon\Carbon::parse($calendar->start_date)->diffInDays(\Carbon\Carbon::parse($calendar->end_date)->addDay()) }}泊
                                </p>
                            </div>
                        </div>
                        
                        <!-- 人数入力 -->
                        <div class="mb-6">
                            <h3 class="text-lg font-semibold mb-2">宿泊人数</h3>
                            
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">
                                        大人 <span class="text-red-500">*</span>
                                    </label>
                                    <select name="adult" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                        @for($i = 1; $i <= 10; $i++)
                                            <option value="{{ $i }}" {{ old('adult') == $i ? 'selected' : '' }}>{{ $i }}名</option>
                                        @endfor
                                    </select>
                                </div>
                                
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">
                                        子供
                                    </label>
                                    <select name="child" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                        @for($i = 0; $i <= 10; $i++)
                                            <option value="{{ $i }}" {{ old('child') == $i ? 'selected' : '' }}>{{ $i }}名</option>
                                        @endfor
                                    </select>
                                </div>
                                
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">
                                        犬
                                    </label>
                                    <select name="dog" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                        @for($i = 0; $i <= 5; $i++)
                                            <option value="{{ $i }}" {{ old('dog') == $i ? 'selected' : '' }}>{{ $i }}頭</option>
                                        @endfor
                                    </select>
                                </div>
                            </div>
                        </div>
                        
                        <!-- 備考 -->
                        <div class="mb-6">
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                備考・ご要望
                            </label>
                            <textarea name="note" rows="4" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" placeholder="アレルギー、特別なご要望などがあればご記入ください">{{ old('note') }}</textarea>
                        </div>
                        
                        <!-- ボタン -->
                        <div class="flex justify-between items-center">
                            <a href="{{ route('reservation.index') }}" class="text-gray-600 hover:text-gray-900">
                                ← 戻る
                            </a>
                            <button type="submit" class="inline-flex items-center px-6 py-3 bg-blue-600 border border-transparent rounded-md font-semibold text-white hover:bg-blue-700">
                                サービスを選択する →
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
```

### Step 10-3: サービス画面（Day 8-10）

```blade
{{-- resources/views/services/index.blade.php --}}
<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            サービス一覧
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach($services as $service)
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    @if($service->image)
                    <img src="{{ asset('storage/' . $service->image) }}" alt="{{ $service->title }}" class="w-full h-48 object-cover">
                    @else
                    <div class="w-full h-48 bg-gray-200 flex items-center justify-center">
                        <span class="text-gray-400">No Image</span>
                    </div>
                    @endif
                    
                    <div class="p-6">
                        <h3 class="text-lg font-semibold mb-2">{{ $service->title }}</h3>
                        <p class="text-gray-600 text-sm mb-4">{{ Str::limit($service->body, 100) }}</p>
                        
                        <div class="flex justify-between items-center mb-4">
                            <span class="text-2xl font-bold text-blue-600">
                                ¥{{ number_format($service->price) }}
                            </span>
                            <span class="text-sm text-gray-500">
                                / {{ $service->unit }}
                            </span>
                        </div>
                        
                        @if($service->stock > 0)
                            <p class="text-xs text-gray-500 mb-4">
                                在庫: {{ $service->stock }}{{ $service->unit }}
                            </p>
                        @endif
                        
                        <form method="POST" action="{{ route('services.show', $service) }}">
                            @csrf
                            <button type="submit" class="w-full bg-blue-600 text-white py-2 rounded hover:bg-blue-700 transition">
                                詳細を見る
                            </button>
                        </form>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </div>
</x-app-layout>
```

### Step 10-4: マイページ（Day 11-13）

```blade
{{-- resources/views/mypage/index.blade.php --}}
<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            マイページ
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- ポイント残高 -->
            <div class="bg-gradient-to-r from-blue-500 to-blue-600 overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6 text-white">
                    <h3 class="text-lg font-semibold mb-2">保有ポイント</h3>
                    <p class="text-4xl font-bold">{{ number_format($user_point ?? 0) }} P</p>
                    
                    @if($pointbalance->count() > 0)
                    <div class="mt-4 space-y-2">
                        @foreach($pointbalance as $balance)
                        <div class="text-sm">
                            <span>{{ $balance->point }}P</span>
                            <span class="ml-2 opacity-80">
                                ({{ \Carbon\Carbon::parse($balance->to)->format('Y年m月末') }}まで有効)
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

            <!-- FREEDAY -->
            @if(Auth::user()->type == \App\Consts\UserConst::TYPE_OWNER && $freedays->count() > 0)
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6 bg-white border-b border-gray-200">
                    <h3 class="text-lg font-semibold mb-4">FREEDAY（利用可能日数）</h3>
                    
                    <div class="space-y-3">
                        @foreach($freedays as $freeday)
                        <div class="flex justify-between items-center border-b pb-3">
                            <div>
                                <p class="font-medium">{{ $freeday->freedays }}泊</p>
                                <p class="text-sm text-gray-600">
                                    {{ \Carbon\Carbon::parse($freeday->end_date)->format('Y年m月末日') }}まで有効
                                </p>
                            </div>
                            <a href="{{ route('reservation.index', ['fr' => $freeday->id]) }}" 
                               class="px-4 py-2 bg-green-600 text-white rounded hover:bg-green-700">
                                予約する
                            </a>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
            @endif

            <!-- 今後の予約 -->
            @if($reservations->count() > 0)
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6 bg-white border-b border-gray-200">
                    <h3 class="text-lg font-semibold mb-4">今後の予約</h3>
                    
                    <div class="space-y-4">
                        @foreach($reservations as $reservation)
                        <div class="border rounded-lg p-4">
                            <div class="flex justify-between items-start">
                                <div>
                                    <p class="font-medium">
                                        {{ \Carbon\Carbon::parse($reservation->checkin_date)->format('Y年m月d日') }}
                                        ～
                                        {{ \Carbon\Carbon::parse($reservation->checkout_date)->format('m月d日') }}
                                        ({{ $reservation->days }}泊)
                                    </p>
                                    <p class="text-sm text-gray-600 mt-1">
                                        {{ $reservation->hotel->name }}
                                    </p>
                                    <p class="text-sm text-gray-600">
                                        大人{{ $reservation->adult }}名
                                        @if($reservation->child > 0) / 子供{{ $reservation->child }}名 @endif
                                        @if($reservation->dog > 0) / 犬{{ $reservation->dog }}頭 @endif
                                    </p>
                                    <p class="mt-2">
                                        @if($reservation->status == 1)
                                            <span class="text-xs bg-blue-100 text-blue-800 px-2 py-1 rounded">申込中</span>
                                        @elseif($reservation->status == 2)
                                            <span class="text-xs bg-yellow-100 text-yellow-800 px-2 py-1 rounded">予約中</span>
                                        @elseif($reservation->status == 3)
                                            <span class="text-xs bg-green-100 text-green-800 px-2 py-1 rounded">予約確定</span>
                                        @endif
                                    </p>
                                </div>
                                <div>
                                    <a href="{{ route('reservation.show', $reservation) }}" 
                                       class="text-blue-600 hover:text-blue-800 text-sm">
                                        詳細 →
                                    </a>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
            @endif

            <!-- プロフィール編集 -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <h3 class="text-lg font-semibold mb-4">アカウント設定</h3>
                    
                    <div class="space-y-2">
                        <a href="{{ route('mypage.edit') }}" class="block p-3 hover:bg-gray-50 rounded">
                            プロフィール編集 →
                        </a>
                        <a href="{{ route('mypage.history') }}" class="block p-3 hover:bg-gray-50 rounded">
                            予約履歴 →
                        </a>
                        <a href="{{ route('mypage.pointlog') }}" class="block p-3 hover:bg-gray-50 rounded">
                            ポイント履歴 →
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
```

---

## Phase 11: バリデーション・Request（1週間）

### Step 11-1: Requestクラス作成

```bash
php artisan make:request ReservationRequest
php artisan make:request ReservationStoreRequest
php artisan make:request UserRequest
php artisan make:request ServiceOrderRequest
php artisan make:request CartRequest
```

#### ReservationRequest
```php
<?php
// app/Http/Requests/ReservationRequest.php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ReservationRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'calendar_id' => 'required|exists:calendars,id',
            'adult' => 'required|integer|min:1|max:10',
            'child' => 'nullable|integer|min:0|max:10',
            'dog' => 'nullable|integer|min:0|max:5',
            'note' => 'nullable|string|max:500',
        ];
    }

    public function messages()
    {
        return [
            'calendar_id.required' => 'カレンダーを選択してください',
            'calendar_id.exists' => '選択されたカレンダーが見つかりません',
            'adult.required' => '大人の人数を入力してください',
            'adult.min' => '大人は最低1名必要です',
            'adult.max' => '大人は最大10名までです',
            'child.max' => '子供は最大10名までです',
            'dog.max' => '犬は最大5頭までです',
            'note.max' => '備考は500文字以内で入力してください',
        ];
    }
}
```

---

## Phase 12: メール機能（3-5日）

### Step 12-1: メール設定

```env
# .env にメール設定追加
MAIL_MAILER=smtp
MAIL_HOST=mailhog
MAIL_PORT=1025
MAIL_USERNAME=null
MAIL_PASSWORD=null
MAIL_ENCRYPTION=null
MAIL_FROM_ADDRESS=noreply@soranoniwa.jp
MAIL_FROM_NAME="${APP_NAME}"
```

### Step 12-2: メールクラス作成

```bash
php artisan make:mail AdminMail
php artisan make:mail InqueryMail
```

```php
<?php
// app/Mail/AdminMail.php

namespace App\Mail;

use Illuminate\Mail\Mailable;
use App\Models\Reservation;

class AdminMail extends Mailable
{
    public $reservation;
    public $type;
    
    public function __construct(Reservation $reservation, $type = 'new')
    {
        $this->reservation = $reservation;
        $this->type = $type;
    }
    
    public function build()
    {
        $subject = match($this->type) {
            'new' => '【空ノ庭】新規予約がありました',
            'cancel' => '【空ノ庭】予約キャンセルがありました',
            default => '【空ノ庭】予約通知',
        };
        
        return $this->subject($subject)
            ->view('emails.admin')
            ->with([
                'reservation' => $this->reservation,
                'user' => $this->reservation->user,
                'type' => $this->type,
            ]);
    }
}
```

### Step 12-3: メール送信処理追加

```php
// ReservationController の store メソッドに追加

use Illuminate\Support\Facades\Mail;
use App\Mail\AdminMail;

public function store(Request $request)
{
    DB::beginTransaction();
    
    try {
        // 予約作成処理...
        
        // メール送信
        // 管理者へ通知
        Mail::to('admin@soranoniwa.jp')->send(new AdminMail($reservation, 'new'));
        
        // ユーザーへ確認メール（オプション）
        // Mail::to($user->email)->send(new ReservationConfirmMail($reservation));
        
        DB::commit();
        
        return redirect()->route('reservation.complete')
            ->with('reservation_id', $reservation->id);
            
    } catch (\Exception $e) {
        DB::rollBack();
        \Log::error('Reservation Error: ' . $e->getMessage());
        return back()->withErrors(['error' => $e->getMessage()]);
    }
}
```

### チェックポイント
- [ ] 全画面が実装され表示される
- [ ] バリデーションが動作
- [ ] エラーメッセージが日本語表示
- [ ] メールが送信される（Mailhogで確認）
- [ ] レスポンシブデザイン対応

---

次のPhase 13-16（最終フェーズ）のファイルも作成しますか？

