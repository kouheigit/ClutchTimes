@extends('layouts.app')
@section('content')
    <link href="{{ asset('css/tos.css') }}" rel="stylesheet">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">{{ __('Register') }}</div>
                    <div class="card-body">
                        　<h3>　　　　　　　　　　　　サインイン/新規登録</h3>
                        <form method="POST" action="{{ route('register') }}">
                            @csrf
                            <!--名前を除外
                        <div class="row mb-3">
                            <label for="name" class="col-md-4 col-form-label text-md-end">{{ __('') }}</label>
                            <div class="col-md-6">
                                <input type ="hidden" input id="name" type="text" class="form-control @error('name') is-invalid @enderror" name="name" value=null required autocomplete="name" autofocus>
                                @error('name')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>-->

                            <div class="row mb-3">
                                <label for="email" class="col-md-4 col-form-label text-md-end">{{ __('') }}</label>

                                <div class="col-md-6">
                                    <input id="email" type="email"
                                           class="form-control @error('email') is-invalid @enderror" name="email"
                                           value="{{ old('email') }}" placeholder="Email" required autocomplete="email">

                                    @error('email')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>
                            </div>

                            <div class="row mb-3">
                                <label for="password" class="col-md-4 col-form-label text-md-end">{{ __('') }}</label>

                                <div class="col-md-6">
                                    <input id="password" type="password"
                                           class="form-control @error('password') is-invalid @enderror" name="password"
                                           placeholder="Password" required autocomplete="new-password">

                                    @error('password')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>
                            </div>

                            <div class="row mb-3">
                                <label for="password-confirm"
                                       class="col-md-4 col-form-label text-md-end">{{ __('Confirm Password') }}</label>

                                <div class="col-md-6">
                                    <input id="password-confirm" type="password" class="form-control"
                                           name="password_confirmation" required autocomplete="new-password">
                                </div>
                            </div>
                            <!--追加部分-->
                            <section>
                            <div class="maincontent">
                                <div class="subcontent">
　　　　　　　　　　　　　　　　　<h4>個人情報の規約</h4>
                            </div>
                                <div class="subcontent">
                                <b>利用規約</b>
                                    <div class="textcontent">
                                    <p>
                                        テキストテキストテキストテキストテキストテキススト
                                        <br>
                                        テキストテキストテキストテキストテキストテキススト
                                        <br>
                                        テキストテキストテキストテキストテキストテキススト
                                    </p>
                                    </div>
                                </div>
                                <div class="checkcontent">
                                    <input type="checkbox" name="checked" required style="transform:scale(2.0)" ;>
                                    <label>　利用規約、個人情報の扱いに同意する</label>
                                </div>
                            </div>
                            </section>
                            <!--追加部分終了-->
                            <div class="row mb-0">
                                <div class="col-md-6 offset-md-4">
                                    <button type="submit" class="btn btn-primary">
                                        {{ __('Register') }}
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
