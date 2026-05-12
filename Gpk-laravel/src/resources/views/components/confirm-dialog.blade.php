{{-- 確認ダイアログコンポーネント --}}
@props(['title' => '確認', 'message' => 'この操作を実行しますか？', 'confirmText' => '実行', 'cancelText' => 'キャンセル'])

<div x-data="{ show: false }" x-show="show" class="fixed inset-0 z-50 overflow-y-auto" style="display: none;">
    <div class="flex items-center justify-center min-h-screen px-4">
        <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" @click="show = false"></div>
        <div class="bg-white rounded-lg overflow-hidden shadow-xl max-w-md w-full z-10">
            <div class="p-6">
                <h3 class="text-lg font-medium text-gray-900 mb-4">{{ $title }}</h3>
                <p class="text-sm text-gray-500 mb-6">{{ $message }}</p>
                <div class="flex justify-end space-x-3">
                    <button @click="show = false" class="px-4 py-2 bg-gray-200 text-gray-700 rounded-md hover:bg-gray-300">
                        {{ $cancelText }}
                    </button>
                    <button @click="$dispatch('confirmed'); show = false" class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700">
                        {{ $confirmText }}
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

