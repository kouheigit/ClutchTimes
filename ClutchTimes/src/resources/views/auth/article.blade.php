@extends('layouts.app')
@section('content')
    <!--テストコード--
    <form method="POST" id="form1"　action="{{ route('home') }}">
        @csrf
        <input type="hidden" name="posttest" value="名前">
        <a href="javascript:void(0)"onclick="this.parentNode.submit()">登録します</a>
    </form>
    <h1>記事表示場面です</h1>
    -->
@endsection
