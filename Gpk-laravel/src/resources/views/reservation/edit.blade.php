<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            予約編集
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            @if($errors->any())
            <x-alert type="error">
                <ul class="list-disc list-inside">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </x-alert>
            @endif

            <form method="POST" action="{{ route('reservation.update', $reservation) }}">
                @csrf
                @method('PUT')
                
                <x-card title="予約情報">
                    <x-form-section title="宿泊情報">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                    チェックイン日
                                </label>
                                <input type="date" 
                                       name="checkin_date" 
                                       value="{{ old('checkin_date', $reservation->checkin_date->format('Y-m-d')) }}"
                                       required
                                       class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                            </div>
                            
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                    チェックアウト日
                                </label>
                                <input type="date" 
                                       name="checkout_date" 
                                       value="{{ old('checkout_date', $reservation->checkout_date->format('Y-m-d')) }}"
                                       required
                                       class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                            </div>
                        </div>
                    </x-form-section>
                    
                    <x-form-section title="宿泊人数">
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                            <x-input name="adult" label="大人" type="number" :value="$reservation->adult" min="1" max="10" required />
                            <x-input name="child" label="子供" type="number" :value="$reservation->child" min="0" max="10" />
                            <x-input name="dog" label="犬" type="number" :value="$reservation->dog" min="0" max="5" />
                        </div>
                    </x-form-section>
                    
                    <x-form-section title="備考">
                        <x-textarea name="note" :value="$reservation->note" rows="4" />
                    </x-form-section>
                </x-card>
                
                <div class="mt-6 flex items-center justify-end space-x-4">
                    <a href="{{ route('reservation.show', $reservation) }}" 
                       class="px-4 py-2 bg-gray-300 text-gray-700 rounded-md hover:bg-gray-400">
                        キャンセル
                    </a>
                    <x-action-button type="submit" variant="primary">
                        更新する
                    </x-action-button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>




















