{{-- ヘッダー部分パーシャル --}}
<header class="bg-white shadow-sm border-b border-gray-200">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between items-center h-16">
            <div class="flex items-center">
                <a href="{{ route('top') }}" class="flex items-center">
                    <span class="text-2xl font-bold text-gray-900">空ノ庭</span>
                </a>
            </div>
            <nav class="hidden md:flex space-x-8">
                <a href="{{ route('top') }}" class="text-gray-700 hover:text-gray-900 px-3 py-2 text-sm font-medium">トップ</a>
                <a href="{{ route('reservation.index') }}" class="text-gray-700 hover:text-gray-900 px-3 py-2 text-sm font-medium">予約</a>
                <a href="{{ route('services.index') }}" class="text-gray-700 hover:text-gray-900 px-3 py-2 text-sm font-medium">サービス</a>
                <a href="{{ route('mypage.index') }}" class="text-gray-700 hover:text-gray-900 px-3 py-2 text-sm font-medium">マイページ</a>
            </nav>
        </div>
    </div>
</header>

