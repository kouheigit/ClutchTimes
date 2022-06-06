@extends('layouts.app')

@section('content')
   @isset($msg)
       <p>{{$msg}}</p>
   @else
   @endisset
    <form method="POST" action="{{ route('admin.addnews') }}">
    　@csrf
       <input type="text" name="msg">
       <input type="submit">
    </form>
@endsection
