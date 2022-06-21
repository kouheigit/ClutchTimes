@extends('layouts.app')
@section('content')
    @foreach ($questions as $val)
        <form method="POST" id="form1" action="{{ route('auth.votepost') }}">
            @csrf
        <input type="hidden" name="question_id" value="{{$val->id}}">
            <input type="hidden" name="user_id" value="{{$user_id}}">
            <h3>question_id→{{$val->id}}</h3>
            <h3>user_id→{{$user_id}}</h3>
        <h3>{{$val->title}}</h3>
        <h3>{{$val->question}}</h3>
            <h1> {{$answer1_title}}{{$show_answer1}}%</h1>
            <h1> {{$answer2_title}}{{$show_answer2}}%</h1>
            @if($answer3_title==null)
            @else
            <h1> {{$answer3_title}}{{$show_answer3}}%</h1>
            @endif
            <input type="radio" name="answer" value="answer1"　required>{{$val->answer1}}
            <input type="radio" name="answer" value="answer2">{{$val->answer2}}
            @if($val->answer3 == null)
            @else
            <input type="radio" name="answer" value="answer3">{{$val->answer3}}
            @endif
            <br>
            <input type="submit" value="投票する">
            <br>
            <br>
        <p>投票の締切日{{$val->end_date}}</p>
    @endforeach

@endsection
