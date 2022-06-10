@extends('layouts.app')
<!--↓cssが全く効かないので現状スルー-->
<link href="{{ asset('css/addnews.css') }}" rel="stylesheet">
<!--css end-->
<seciton>
    <div class="maincontent">
@section('content')

        @isset($complete)
            <p>{{$complete}}</p>
        @else
        @endisset
        <form method="POST" action="{{ route('admin.addnews') }}">
            　@csrf
            @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
            @if($errors->has('title'))
            @endif
            <!--タイトル-->
            <p>タイトル</p>
            <input type="text" name="title" required>
            <br>
            <br>
            <label for="date">投稿日時</label>
            <input type="date" id="date" name="date" value="" required/>
            <input type="time" id="time" name="time" value="" required/>
            <!--本文-->
            <br>
            <br>
            <p>本文</p>
            @if($errors->has('comment'))
            @endif
            <textarea name="comment" cols="40" rows="8" required></textarea>
            <br>
            <input type="submit" value="投稿する">
        </form>
        @endsection
    </div>
    </seciton>
