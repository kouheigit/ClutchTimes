@props(['status', 'type' => 'reservation'])

@php
if ($type === 'reservation') {
    $statuses = [
        1 => ['label' => '申込中', 'class' => 'bg-blue-100 text-blue-800'],
        2 => ['label' => '予約中', 'class' => 'bg-yellow-100 text-yellow-800'],
        3 => ['label' => '予約確定', 'class' => 'bg-green-100 text-green-800'],
        4 => ['label' => 'チェックイン済', 'class' => 'bg-purple-100 text-purple-800'],
        5 => ['label' => 'チェックアウト済', 'class' => 'bg-gray-100 text-gray-800'],
        8 => ['label' => 'キャンセル中', 'class' => 'bg-orange-100 text-orange-800'],
        9 => ['label' => 'キャンセル', 'class' => 'bg-red-100 text-red-800'],
    ];
} elseif ($type === 'payment') {
    $statuses = [
        0 => ['label' => '未払い', 'class' => 'bg-gray-100 text-gray-800'],
        1 => ['label' => '支払済み', 'class' => 'bg-green-100 text-green-800'],
    ];
} else {
    $statuses = [
        0 => ['label' => '無効', 'class' => 'bg-gray-100 text-gray-800'],
        1 => ['label' => '有効', 'class' => 'bg-green-100 text-green-800'],
    ];
}

$statusInfo = $statuses[$status] ?? ['label' => '不明', 'class' => 'bg-gray-100 text-gray-800'];
@endphp

<span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium {{ $statusInfo['class'] }}">
    {{ $statusInfo['label'] }}
</span>




















