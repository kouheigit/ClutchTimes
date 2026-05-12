{{-- 折りたたみコンポーネント --}}
@props(['open' => false])

<div x-data="{ open: {{ $open ? 'true' : 'false' }} }" {{ $attributes }}>
    <div x-show="open" x-collapse>
        {{ $slot }}
    </div>
</div>

