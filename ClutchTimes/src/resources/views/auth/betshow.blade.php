@extends('layouts.app')
@section('content')
    <h1>現在の予想</h1>

    @foreach ($questions as $val)
        <h1>{{$val->id}}</h1>
        <form method="GET" id="form1" action="{{ route('auth.vote') }}">
            @csrf
            <input type="hidden" name="id" value="{{$val->id}}">
            <h3>{{$val->title}}</h3>
            <h3>{{$val->question}}</h3>
            <p>{{$val->answer1}}</p>
            <p>{{$val->answer2}}</p>
            <p>{{$val->answer3}}</p>
            <p>投票の締切日{{$val->end_date}}</p>
            <input type="submit" value="投票画面へ">
        </form>
        <br>
    @endforeach
    <!--testとして入れている -->
    @foreach($tests as $test1)
        {{$test1}}
    @endforeach
   <!--testend -->
    <!--auth.homeのformから取得する-->
@endsection
