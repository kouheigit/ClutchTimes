{{-- エラーメッセージパーシャル --}}
@if($errors->any())
<div class="mb-4 bg-red-50 border border-red-200 text-red-800 px-4 py-3 rounded relative" role="alert">
    <strong class="font-bold">エラーが発生しました:</strong>
    <ul class="mt-2 list-disc list-inside">
        @foreach($errors->all() as $error)
        <li>{{ $error }}</li>
        @endforeach
    </ul>
</div>
@endif

