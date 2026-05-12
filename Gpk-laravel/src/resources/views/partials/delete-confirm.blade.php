{{-- 削除確認パーシャル --}}
@props(['action' => '#', 'method' => 'DELETE', 'message' => 'この操作は取り消せません。本当に削除しますか？'])

<form action="{{ $action }}" method="POST" onsubmit="return confirm('{{ $message }}')">
    @csrf
    @method($method)
    <button type="submit" class="px-4 py-2 bg-red-600 text-white rounded-md hover:bg-red-700">
        削除
    </button>
</form>

