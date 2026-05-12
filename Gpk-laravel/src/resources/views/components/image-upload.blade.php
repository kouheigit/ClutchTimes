{{-- 画像アップロードコンポーネント --}}
@props(['currentImage' => null, 'name' => 'image'])

<div class="flex items-center space-x-4">
    @if($currentImage)
    <img src="{{ asset('storage/' . $currentImage) }}" alt="現在の画像" class="h-20 w-20 object-cover rounded">
    @endif
    <div>
        <label class="cursor-pointer inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
            </svg>
            画像を選択
            <input type="file" 
                   name="{{ $name }}"
                   accept="image/*"
                   {{ $attributes->merge(['class' => 'sr-only']) }}>
        </label>
    </div>
</div>

