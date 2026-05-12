{{-- クッキー同意パーシャル --}}

@if(!session('cookie_consent'))
<div x-data="{ show: true }" 
     x-show="show"
     x-transition
     class="fixed bottom-0 left-0 right-0 bg-gray-900 text-white p-4 shadow-lg z-50"
     style="display: none;">
    <div class="max-w-7xl mx-auto flex items-center justify-between">
        <div class="flex-1">
            <p class="text-sm">このサイトでは、より良い体験を提供するためにクッキーを使用しています。サイトを利用することで、クッキーの使用に同意したものとみなされます。</p>
        </div>
        <div class="ml-4 flex space-x-2">
            <form action="{{ route('cookie.consent') }}" method="POST">
                @csrf
                <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">
                    同意する
                </button>
            </form>
            <button @click="show = false" class="px-4 py-2 bg-gray-700 text-white rounded hover:bg-gray-600">
                閉じる
            </button>
        </div>
    </div>
</div>
@endif

