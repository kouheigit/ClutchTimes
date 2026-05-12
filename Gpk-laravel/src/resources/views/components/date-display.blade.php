@props(['date', 'format' => 'Y年m月d日', 'showWeekday' => true])

@php
$carbon = \Carbon\Carbon::parse($date);
$formatted = $carbon->format($format);
if ($showWeekday) {
    $formatted .= '(' . $carbon->locale('ja')->isoFormat('ddd') . ')';
}
@endphp

{{ $formatted }}




















