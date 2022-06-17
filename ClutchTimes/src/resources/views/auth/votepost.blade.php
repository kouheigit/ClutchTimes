@extends('layouts.app')
@section('content')
   <!-- <META http-equiv="Refresh" content="3;URL={{ route('auth.betshow') }}">-->
   @if($error_message == null)
       <h1>投票が完了しました</h1>
       <p>3秒後に投票一覧に戻ります</p>
   @else
       <p>投票ができませんでした、以前にも投票しました。</p>
   @endif
@endsection
