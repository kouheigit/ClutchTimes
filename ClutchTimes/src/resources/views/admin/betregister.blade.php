@extends('layouts.app')

@section('content')
    <h2>Bet</h2>
    <form method="POST" action="{{ route('admin.betregisterpost') }}">
        @csrf
   <h3>試合情報</h3>
   <input type="text" name="title" required>
   <h3>予想問題</h3>
   <textarea name="question"rows="6" cols="40" required></textarea>
    <h3>選択肢</h3>
    <input type="text" name="answer1" required>
    <br>
    <br>
    <input type="text" name="answer2" required>
    <br>
    <br>
    <input type="text" name="answer3" >
    <h3>投稿時刻</h3>
    <input type="date" id="date" name="date"  required/>
    <input type="time" id="time" name="time"  required/>
    <br>
    <h3>締切時刻</h3>
    <input type="date" id="enddate" name="enddate"  required/>
    <input type="time" id="endtime" name="endtime"  required/>
        <br>
        <br>
        <input type="submit" value="投稿する">
    </form>
@endsection
