{{-- モーダルトリガーパーシャル --}}
@props(['target' => 'modal', 'label' => '開く'])

<button type="button" 
        onclick="document.getElementById('{{ $target }}').classList.remove('hidden')"
        class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700">
    {{ $label }}
</button>

