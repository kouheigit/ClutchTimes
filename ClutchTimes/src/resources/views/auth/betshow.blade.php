@extends('layouts.app')
@section('content')
    <h1>現在の予想</h1>
    @foreach ($questions as $val)
        <h1>{{$val->id}}</h1>
        <form method="POST" id="form1" action="{{ route('auth.vote') }}">
            @csrf
            <input type="hidden" name="id" value="{{$val->id}}">
            <h3>{{$val->title}}</h3>
            <h3>{{$val->question}}</h3>
            <p>{{$val->answer1}} {{ $show_value[$val->id][0] }}</p>
            <p>{{$val->answer2}} {{ $show_value[$val->id][1] }}</p>
            <p>{{$val->answer3}} {{ $show_value[$val->id][2] }}</p>
            <p>投票の締切日{{$val->end_date}}</p>
            <input type="submit" value="投票画面へ">
        </form>
        <br>
        <br>
    @endforeach
    <br>
    <br>
    <!--testend -->
    <!--auth.homeのformから取得する-->
@endsection
