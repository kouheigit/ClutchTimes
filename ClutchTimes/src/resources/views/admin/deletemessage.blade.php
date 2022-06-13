@extends('layouts.app')
@section('content')
    <META http-equiv="Refresh" content="3;URL={{ route('admin.deletearticle') }}">
    <h1>タイトル名{{$deletename}}の記事を削除が完了しました</h1>
    <p>3秒後に記事削除画面に戻ります</p>
@endsection
