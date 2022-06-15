@extends('layouts.app')

@section('content')
    <h2>Bet</h2>
    <form method="POST" action="{{ route('admin.betregisterpost') }}">
        @csrf
        @foreach($errors->all() as $error)
            <li>{{ $error }}</li>
        @endforeach
        <h3>試合情報</h3>
        <input type="text" name="title" value="{{old('title')}}" required>
        <h3>予想問題</h3>
        <textarea type="text" name="question" rows="6" cols="40" {{old('question')}} required>{{old('question')}}</textarea>
        <h3>選択肢</h3>
        <input type="text" name="answer1" value="{{old('answer1')}}" required>
        <br>
        <br>
        <input type="text" name="answer2" value="{{old('answer2')}}" required>
        <br>
        <br>
        <input type="text" name="answer3" value="{{old('answer3')}}">
        <h3>投稿時刻</h3>
        <input type="date" id="date" name="date" value="{{old('date')}}" required/>
        <input type="time" id="time" name="time" value="{{old('time')}}" required/>
        <br>
        <h3>締切時刻</h3>
        <input type="date" id="enddate" name="enddate" value="{{old('enddate')}}" required/>
        <input type="time" id="endtime" name="endtime" value="{{old('endtime')}}" required/>
        <br>
        <br>
        <input type="submit" value="投稿する">
    </form>
@endsection
