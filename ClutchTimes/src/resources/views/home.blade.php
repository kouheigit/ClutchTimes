@extends('layouts.app')

@section('content')
    <h1>いやっほう</h1>
    {{$msg}}
@foreach((array)$test3 as $syamu)
   {{ $syamu }}
@endforeach

    {{$tests}}
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
