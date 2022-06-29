@extends('layouts.app')
@section('content')

    @foreach ($news as $val)
        <p>投稿時刻</p>
        <b>{{$val->date}}</b>
        <p>タイトル</p>
        　　　<b>{{$val->title}}</b>
        <p>記事本文</p>
            <b>{{$val->text}}</b>
   @endforeach
            <!--テストコード--
    <form method="POST" id="form1"　action="{{ route('home') }}">
        @csrf
            <input type="hidden" name="posttest" value="名前">
            <a href="javascript:void(0)"onclick="this.parentNode.submit()">登録します</a>
        </form>
        <h1>記事表示場面です</h1>
-->
@endsection
