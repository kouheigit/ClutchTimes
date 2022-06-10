@extends('layouts.app')

@section('content')
    <h1>記事表示場面です</h1>
    <form method="POST" id="form1"　action="{{ route('auth.article') }}">
        @csrf
        <input type="hidden" name="{{$showvalue}}" value="名前">
        <a href="javascript:void(0)"onclick="this.parentNode.submit()">{{$showvalue}}</a>
    </form>

    <form method="POST" id="form1"　action="{{ route('auth.article') }}">
        @csrf
        <input type="hidden" name="{{$showvalue1}}" value="名前">
        <a href="javascript:void(0)"onclick="this.parentNode.submit()">{{$showvalue1}}</a>
    </form>

    <form method="POST" id="form1"　action="{{ route('auth.article') }}">
        @csrf
        <input type="hidden" name="{{$showvalue2}}" value="名前">
        <a href="javascript:void(0)"onclick="this.parentNode.submit()">{{$showvalue2}}</a>
    </form>


    <!--
    <form method="post" name="form1">
    <h1>{{$showvalue}}</h1>
    <h1>{{$showvalue1}}</h1>
    <h1>{{$showvalue2}}</h1>-->
    <!--homeの大半は除外-->
    <!--
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">

                <div class="card-header">{{ __('Dashboard') }}</div>

                <div class="card-body">
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif

                    {{ __('You are logged in!') }}

                </div>
            </div>
        </div>
    </div>
</div>-->
@endsection
