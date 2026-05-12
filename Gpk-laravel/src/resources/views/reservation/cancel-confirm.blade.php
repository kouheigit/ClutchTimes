<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            予約キャンセル確認
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <x-card title="予約キャンセルの確認">
                <x-alert type="warning">
                    この操作は取り消せません。本当にキャンセルしますか？
                </x-alert>
                
                <div class="mt-6">
                    <x-partials.reservation-summary :reservation="$reservation" />
                </div>
                
                <form method="POST" action="{{ route('reservation.cancel', $reservation) }}" class="mt-6">
                    @csrf
                    @method('POST')
                    
                    <x-form-section title="キャンセル理由（任意）">
                        <x-textarea name="cancel_reason" placeholder="キャンセルの理由を入力してください" rows="3" />
                    </x-form-section>
                    
                    <div class="mt-6 flex items-center justify-end space-x-4">
                        <a href="{{ route('reservation.show', $reservation) }}" 
                           class="px-4 py-2 bg-gray-300 text-gray-700 rounded-md hover:bg-gray-400">
                            戻る
                        </a>
                        <x-action-button type="submit" variant="danger">
                            キャンセルする
                        </x-action-button>
                    </div>
                </form>
            </x-card>
        </div>
    </div>
</x-app-layout>




















