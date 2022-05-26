@extends('layouts.app')

@section('content')
    <div class="maincontent">
        <h1>利用規約</h1>
        <h1>個人情報の規約</h1>
        <br>
        <h2>
            <b>利用規約</b>
        </h2>
        <div class="textxcontent">
            <p>
                テキストテキストテキストテキストテキストテキススト
                テキストテキストテキストテキストテキストテキスト
                テキストテキストテキストテキストテキストテキスト
                <br>
                テキストテキストテキストテキストテキストテキススト
                テキストテキストテキストテキストテキストテキスト
                テキストテキストテキストテキストテキストテキスト
                <br>
                テキストテキストテキストテキストテキストテキススト
                テキストテキストテキストテキストテキストテキスト
                テキストテキストテキストテキストテキストテキスト
            </p>
            <!--送信-->
            <!--<form method="POST" action="{{ route('home') }}">-->
            <!--本当は下の奴にする-->
            <form method="POST" action="{{ route('tos') }}">
                {{ csrf_field() }}
                <div class=#checkcontent">
                    <input type="checkbox" name="checked" required style="transform:scale(2.0)" ;>
                    <label>　利用規約、個人情報の扱いに同意する</label>
                </div>
                @if ($checkvalue != null)
                    <!--<p>{{$checkvalue}}</p>-->
                @else
                    <p></p>
                @endif
                <br>
                <input type="submit" value="次へ">
            </form>
        </div>
    </div>
@endsection
