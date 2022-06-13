@extends('layouts.app')

@section('content')
    <!--slackの指示通りに修正する(aタグとshowvalueの2箇所)-->
    @foreach ($news as $val)
    <form method="POST" id="form1" action="{{ route('auth.article') }}">
        @csrf
        <input type="hidden" name="articlevalue" value="{{$val->id}}">
        <a href="javascript:void(0)" onclick="this.parentNode.submit()">{{ $val->date }}{{ $val->title }}</a>
    </form>
    @endforeach
<!--
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
z1
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
