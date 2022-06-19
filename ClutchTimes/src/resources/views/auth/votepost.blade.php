@extends('layouts.app')
@section('content')
   <!-- <META http-equiv="Refresh" content="3;URL={{ route('auth.betshow') }}">-->
   @if($error_message == null)
       <h1>投票が完了しました</h1>
       <p>3秒後に投票一覧に戻ります</p>
   @else
       <p>投票ができませんでした、以前にも投票しました。</p>
   @endif
   <!--再度投票処理が却下されたら以下のものを復活させろ-->
   <!--
   {{$error_message}}
   <META http-equiv="Refresh" content="3;URL={{ route('auth.betshow') }}">
   <h1>投票が完了しました</h1>
   <p>3秒後に投票一覧に戻ります</p>-->
    <!--復活箇所は以上-->
@endsection
