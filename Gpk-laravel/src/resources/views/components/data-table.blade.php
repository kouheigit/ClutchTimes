{{-- データテーブルコンポーネント --}}
@props(['headers' => [], 'emptyMessage' => 'データがありません'])

<div class="overflow-x-auto">
    <table class="min-w-full divide-y divide-gray-200">
        <thead class="bg-gray-50">
            <tr>
                @foreach($headers as $header)
                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                    {{ $header }}
                </th>
                @endforeach
            </tr>
        </thead>
        <tbody class="bg-white divide-y divide-gray-200">
            {{ $slot }}
        </tbody>
    </table>
    @if(empty($slot->toHtml()) || trim($slot->toHtml()) === '')
    <div class="text-center py-12">
        <p class="text-gray-500">{{ $emptyMessage }}</p>
    </div>
    @endif
</div>

