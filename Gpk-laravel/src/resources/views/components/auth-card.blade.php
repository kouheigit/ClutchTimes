<div class="min-h-screen flex flex-col sm:justify-center items-center pt-6 sm:pt-0">
    <div class="mb-8">
        {{ $logo }}
    </div>

    <div class="w-full sm:max-w-md mt-6 px-8 py-8 bg-white bg-opacity-95 backdrop-blur-sm shadow-2xl overflow-hidden sm:rounded-lg border border-gray-200">
        {{ $slot }}
    </div>
    
    <!-- CONTACT Section -->
    <div class="w-full sm:max-w-4xl mt-12 px-6 py-8 bg-white bg-opacity-95 backdrop-blur-sm shadow-xl sm:rounded-lg border border-gray-200">
        <h2 class="text-2xl font-bold text-center mb-6 text-gray-800">CONTACT</h2>
        <h3 class="text-xl font-semibold text-center mb-4 text-gray-700">お問い合わせ</h3>
        <div class="border-t border-gray-300 pt-6">
            <div class="flex flex-col items-center space-y-6">
                <div class="flex items-center space-x-6">
                    <!-- LINE Icon -->
                    <div class="flex items-center space-x-2">
                        <svg class="w-8 h-8 text-green-500" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M19.365 9.863c.349 0 .63.285.63.631 0 .345-.281.63-.63.63H17.61v1.125h1.755c.349 0 .63.283.63.63 0 .344-.281.629-.63.629H17.61v1.125c0 .345-.282.63-.63.63-.345 0-.63-.285-.63-.63v-1.125h-1.756c-.348 0-.629-.285-.629-.629 0-.347.281-.63.629-.63h1.756v-1.125h-1.756c-.348 0-.629-.285-.629-.63 0-.346.281-.631.629-.631h1.756v-1.126c0-.345.285-.629.63-.629.348 0 .63.284.63.629v1.126h1.755zm-2.955 3.016H5.062c-1.748 0-3.165-1.437-3.165-3.206 0-1.768 1.417-3.205 3.165-3.205h11.348c1.749 0 3.165 1.437 3.165 3.205 0 1.769-1.416 3.206-3.165 3.206zM5.062 7.393c-1.184 0-2.145.975-2.145 2.18 0 1.202.961 2.178 2.145 2.178h11.348c1.184 0 2.144-.976 2.144-2.178 0-1.205-.96-2.18-2.144-2.18H5.062zm15.303 2.18c0-1.205.96-2.18 2.145-2.18 1.184 0 2.144.975 2.144 2.18 0 1.202-.96 2.178-2.144 2.178-1.185 0-2.145-.976-2.145-2.178zm1.314 9.926c-.348 0-.63.285-.63.63v1.125H2.145c-1.184 0-2.145-.976-2.145-2.18 0-1.204.961-2.179 2.145-2.179h15.504c.348 0 .63.284.63.629 0 .346-.282.631-.63.631H2.145c-.48 0-.87.39-.87.869 0 .481.39.87.87.87h17.904v1.125c0 .345.283.63.63.63.345 0 .629-.285.629-.63v-1.125h1.755c.348 0 .629-.285.629-.63 0-.345-.281-.63-.629-.63h-1.755v-1.125c0-.345-.284-.63-.629-.63z"/>
                        </svg>
                        <span class="text-gray-700 font-medium">LINE</span>
                    </div>
                    <!-- MAIL Icon -->
                    <div class="flex items-center space-x-2">
                        <svg class="w-8 h-8 text-gray-600" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M20 4H4c-1.1 0-1.99.9-1.99 2L2 18c0 1.1.9 2 2 2h16c1.1 0 2-.9 2-2V6c0-1.1-.9-2-2-2zm0 4l-8 5-8-5V6l8 5 8-5v2z"/>
                        </svg>
                        <span class="text-gray-700 font-medium">MAIL</span>
                    </div>
                </div>
                <div class="text-center space-y-2">
                    <p class="text-sm text-gray-600 font-medium">【ご予約の受付時間】 10:00~18:00</p>
                    <p class="text-xs text-gray-500">内容によっては、お返事にお時間をいただく場合がございます。</p>
                    <p class="text-xs text-gray-500">あらかじめご了承くださいませ。</p>
                </div>
            </div>
        </div>
    </div>
    
    <!-- LINKS Section -->
    <div class="w-full sm:max-w-4xl mt-8 px-6 py-8 bg-white bg-opacity-95 backdrop-blur-sm shadow-xl sm:rounded-lg border border-gray-200 mb-12">
        <h2 class="text-2xl font-bold text-center mb-6 text-gray-800">LINKS</h2>
        <h3 class="text-xl font-semibold text-center mb-4 text-gray-700">関連リンク</h3>
        <div class="border-t border-gray-300 pt-6">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div>
                    <h4 class="text-lg font-semibold mb-3 text-gray-800">TEITAKU事務局</h4>
                    <p class="text-sm text-gray-600 mb-2">〒389-0102</p>
                    <p class="text-sm text-gray-600 mb-2">長野県北佐久郡軽井沢町軽井沢813-1 KKビル3F</p>
                    <p class="text-sm text-gray-600 mb-4">(営業時間 10:00~18:00)</p>
                    <div class="flex space-x-3">
                        <svg class="w-5 h-5 text-gray-600" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M6.62 10.79c1.44 2.83 3.76 5.14 6.59 6.59l2.2-2.2c.27-.27.67-.36 1.02-.24 1.12.37 2.33.57 3.57.57.55 0 1 .45 1 1V20c0 .55-.45 1-1 1-9.39 0-17-7.61-17-17 0-.55.45-1 1-1h3.5c.55 0 1 .45 1 1 0 1.25.2 2.45.57 3.57.11.35.03.74-.25 1.02l-2.2 2.2z"/>
                        </svg>
                        <svg class="w-5 h-5 text-gray-600" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-2 15l-5-5 1.41-1.41L10 14.17l7.59-7.59L19 8l-9 9z"/>
                        </svg>
                        <svg class="w-5 h-5 text-gray-600" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M20 4H4c-1.1 0-1.99.9-1.99 2L2 18c0 1.1.9 2 2 2h16c1.1 0 2-.9 2-2V6c0-1.1-.9-2-2-2zm0 4l-8 5-8-5V6l8 5 8-5v2z"/>
                        </svg>
                    </div>
                </div>
                <div>
                    <h4 class="text-lg font-semibold mb-3 text-gray-800">運営施設一覧</h4>
                    <p class="text-sm text-gray-600">KatoPleasure Group</p>
                </div>
                <div>
                    <h4 class="text-lg font-semibold mb-3 text-gray-800">SNS</h4>
                    <div class="flex space-x-4">
                        <svg class="w-6 h-6 text-gray-600" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zm0-2.163c-3.259 0-3.667.014-4.947.072-4.358.2-6.78 2.618-6.98 6.98-.059 1.281-.073 1.689-.073 4.948 0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98 1.281.058 1.689.072 4.948.072 3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98-1.281-.059-1.69-.073-4.949-.073zm0 5.838c-3.403 0-6.162 2.759-6.162 6.162s2.759 6.163 6.162 6.163 6.162-2.759 6.162-6.163c0-3.403-2.759-6.162-6.162-6.162zm0 10.162c-2.209 0-4-1.79-4-4 0-2.209 1.791-4 4-4s4 1.791 4 4c0 2.21-1.791 4-4 4zm6.406-11.845c-.796 0-1.441.645-1.441 1.44s.645 1.44 1.441 1.44c.795 0 1.439-.645 1.439-1.44s-.644-1.44-1.439-1.44z"/>
                        </svg>
                        <svg class="w-6 h-6 text-gray-600" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/>
                        </svg>
                    </div>
                </div>
            </div>
        </div>
        <div class="mt-8 pt-6 border-t border-gray-300 text-center">
            <p class="text-sm text-gray-500">Copyright © KatoPleasure Group All Rights Reserved.</p>
        </div>
    </div>
</div>
