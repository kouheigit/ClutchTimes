<footer class="bg-gray-800 text-white mt-12">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <div class="grid grid-cols-1 md:grid-cols-4 gap-8">
            <div>
                <h3 class="text-lg font-semibold mb-4">空ノ庭</h3>
                <p class="text-sm text-gray-300">
                    GLAMDAY STYLE TEITAKU<br>
                    軽井沢の宿泊予約システム
                </p>
            </div>
            
            <div>
                <h4 class="text-md font-semibold mb-4">TEITAKU事務局</h4>
                <p class="text-sm text-gray-300 mb-2">〒389-0102</p>
                <p class="text-sm text-gray-300 mb-2">長野県北佐久郡軽井沢町軽井沢813-1 KKビル3F</p>
                <p class="text-sm text-gray-300 mb-4">(営業時間 10:00~18:00)</p>
                <div class="flex space-x-3">
                    <a href="tel:" class="text-gray-400 hover:text-white">
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M6.62 10.79c1.44 2.83 3.76 5.14 6.59 6.59l2.2-2.2c.27-.27.67-.36 1.02-.24 1.12.37 2.33.57 3.57.57.55 0 1 .45 1 1V20c0 .55-.45 1-1 1-9.39 0-17-7.61-17-17 0-.55.45-1 1-1h3.5c.55 0 1 .45 1 1 0 1.25.2 2.45.57 3.57.11.35.03.74-.25 1.02l-2.2 2.2z"/>
                        </svg>
                    </a>
                    <a href="#" class="text-gray-400 hover:text-white">
                        <img src="{{ asset('images/icons/icon-line.svg') }}" alt="LINE" class="w-5 h-5">
                    </a>
                    <a href="mailto:" class="text-gray-400 hover:text-white">
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M20 4H4c-1.1 0-1.99.9-1.99 2L2 18c0 1.1.9 2 2 2h16c1.1 0 2-.9 2-2V6c0-1.1-.9-2-2-2zm0 4l-8 5-8-5V6l8 5 8-5v2z"/>
                        </svg>
                    </a>
                </div>
            </div>
            
            <div>
                <h4 class="text-md font-semibold mb-4">リンク</h4>
                <ul class="space-y-2 text-sm">
                    <li><a href="{{ route('top') }}" class="text-gray-300 hover:text-white">トップページ</a></li>
                    <li><a href="{{ route('reservation.index') }}" class="text-gray-300 hover:text-white">予約管理</a></li>
                    <li><a href="{{ route('services.index') }}" class="text-gray-300 hover:text-white">サービス</a></li>
                    <li><a href="{{ route('news.index') }}" class="text-gray-300 hover:text-white">お知らせ</a></li>
                    <li><a href="#" class="text-gray-300 hover:text-white">運営施設一覧</a></li>
                </ul>
            </div>
            
            <div>
                <h4 class="text-md font-semibold mb-4">サポート・SNS</h4>
                <ul class="space-y-2 text-sm mb-4">
                    <li><a href="{{ route('mypage.index') }}" class="text-gray-300 hover:text-white">マイページ</a></li>
                    <li><a href="{{ route('information.index') }}" class="text-gray-300 hover:text-white">利用規約</a></li>
                </ul>
                <div class="flex space-x-4">
                    <a href="#" class="text-gray-400 hover:text-white">
                        <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zm0-2.163c-3.259 0-3.667.014-4.947.072-4.358.2-6.78 2.618-6.98 6.98-.059 1.281-.073 1.689-.073 4.948 0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98 1.281.058 1.689.072 4.948.072 3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98-1.281-.059-1.69-.073-4.949-.073zm0 5.838c-3.403 0-6.162 2.759-6.162 6.162s2.759 6.163 6.162 6.163 6.162-2.759 6.162-6.163c0-3.403-2.759-6.162-6.162-6.162zm0 10.162c-2.209 0-4-1.79-4-4 0-2.209 1.791-4 4-4s4 1.791 4 4c0 2.21-1.791 4-4 4zm6.406-11.845c-.796 0-1.441.645-1.441 1.44s.645 1.44 1.441 1.44c.795 0 1.439-.645 1.439-1.44s-.644-1.44-1.439-1.44z"/>
                        </svg>
                    </a>
                    <a href="#" class="text-gray-400 hover:text-white">
                        <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/>
                        </svg>
                    </a>
                </div>
            </div>
        </div>
        
        <div class="mt-8 pt-8 border-t border-gray-700 text-center text-sm text-gray-400">
            <p>Copyright © KatoPleasure Group All Rights Reserved.</p>
        </div>
    </div>
</footer>







