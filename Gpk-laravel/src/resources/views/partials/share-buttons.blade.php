{{-- シェアボタンパーシャル --}}
@props(['url' => null, 'title' => null])

<div class="flex items-center space-x-2">
    <span class="text-sm text-gray-700">シェア:</span>
    <a href="https://twitter.com/intent/tweet?url={{ urlencode($url ?? request()->url()) }}&text={{ urlencode($title ?? '') }}" 
       target="_blank"
       class="px-3 py-1 text-sm bg-blue-500 text-white rounded hover:bg-blue-600">
        Twitter
    </a>
    <a href="https://www.facebook.com/sharer/sharer.php?u={{ urlencode($url ?? request()->url()) }}" 
       target="_blank"
       class="px-3 py-1 text-sm bg-blue-600 text-white rounded hover:bg-blue-700">
        Facebook
    </a>
</div>

