{{-- 日付範囲ピッカーコンポーネント --}}
@props(['startDate' => null, 'endDate' => null])

<div class="flex items-center space-x-2">
    <input type="date" 
           name="start_date"
           value="{{ $startDate }}"
           {{ $attributes->merge(['class' => 'block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm']) }}>
    <span class="text-gray-500">〜</span>
    <input type="date" 
           name="end_date"
           value="{{ $endDate }}"
           {{ $attributes->merge(['class' => 'block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm']) }}>
</div>

