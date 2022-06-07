@extends('layouts.app')
@section('content')
    <!--テスト表示させる-->
    @isset($title)
        <p>{{$title}}</p>
    @else
    @endisset
    @isset($comment)
        <p>{{$comment}}</p>
    @else
    @endisset
    @isset($date)
        <p>{{$date}}</p>
    @else
    @endisset
    @isset($today)
        <p>{{$today}}</p>
    @else
    @endisset
    <!--テスト表示終了-->

    <form method="POST" action="{{ route('admin.addnews') }}">
        　@csrf
        <!--タイトル-->
        <p>タイトル</p>
        <input type="text" name="title" required>
        <!--投稿日時-->
        <p>投稿日時</p>
        <label for="date">投稿時刻</label>
        <input type="date" id="date" name="date" value="" required/>
        <input type="time" id="time" name="time" value="" required/>
        <!--本文-->
        <p>本文</p>
        <textarea name="comment" cols="40" rows="8" required></textarea>
        <br>
        <input type="submit" value="投稿する">
    </form>
@endsection
