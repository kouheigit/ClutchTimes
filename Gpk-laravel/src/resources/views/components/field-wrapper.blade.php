{{-- フィールドラッパーコンポーネント --}}
@props(['label' => null, 'error' => null, 'required' => false, 'help' => null])

<div class="mb-4">
    @if($label)
    <x-field-label :required="$required">{{ $label }}</x-field-label>
    @endif
    <div class="mt-1">
        {{ $slot }}
    </div>
    @if($help)
    <x-help-text>{{ $help }}</x-help-text>
    @endif
    @if($error)
    <x-field-error :message="$error" />
    @endif
</div>

