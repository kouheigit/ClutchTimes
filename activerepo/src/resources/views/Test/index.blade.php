@extends('layouts.testapp')

@section('content')

    <!-- 検索フォーム -->
    <form method="GET" action="{{ route('test.index') }}" class="search-form">
        <b>検索</b>
        <input type="search" name="search" value="{{ request('search') }}">
        <input type="submit" value="検索">
    </form>

    <!-- 並び順変更リンク -->
    <a href="{{ route('test.index', ['order' => $order === 'asc' ? 'desc' : 'asc', 'search' => request('search')]) }}">
        並び順: {{ $order === 'asc' ? '昇順 ▲' : '降順 ▼' }}
    </a>

    <!-- スレッド一覧 -->
    <ul class="thread-list">
        @foreach($threads as $thread)
            <li>
                <p class="thread-meta">
                    <b>{{ $thread->id }} : {{ $thread->name }}</b> {{ $thread->created_at->format('Y/m/d H:i') }} ID:{{ $thread->generateid }}
                </p>
                <p class="thread-content">{{ $thread->comment }}</p>
                @if(!empty($thread->filename))
                    <img src="{{ asset('images/' . $thread->filename) }}" width="193" height="130">
                @endif
            </li>
        @endforeach
    </ul>

    <!-- ページネーション -->

    <div class="pagination">
        {{ $threads->appends(request()->input())->links() }}
    </div>

    <!-- 新規投稿フォーム -->
    <form method="POST" action="{{ route('test.store') }}" id="new-post" enctype="multipart/form-data">
   {{-- <form method="POST" action="{{ route('test.store') }}" id="new-post">--}}
        @csrf
        <table class="post-form">
            <tr>
                <td>名前</td>
                <td><input type="text" name="name" value="{{ old('name') }}" class="small-input"></td>
            </tr>
            <tr>
                <td>タイトル</td>
                <td><input type="text" name="title" value="{{ old('title') }}" class="small-input"></td>
            </tr>
            <tr>
                <td>コメント</td>
                <td><textarea name="comment" rows="3" class="small-textarea">{{ old('comment') }}</textarea></td>
            </tr>
            <tr>
                <td>画像</td>
                <td><input type="file" name="image"></td>
            </tr>
        </table>
        <input type="submit" value="書き込む">
    </form>
@endsection




