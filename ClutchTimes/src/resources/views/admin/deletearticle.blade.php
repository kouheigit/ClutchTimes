@extends('layouts.app')
@section('content')
    <h3>記事を削除する</h3>
    <b>未投稿の記事一覧</b>
    @foreach ($hidden_news as $val)
        <form method="POST" id="form1" action="{{ route('admin.deletemessage') }}">
            @csrf
            <input type="hidden" name="deleteid" value="{{$val->id}}">
            <!--あえてアンカータグにしている（href入れない）Pタグだと改行される-->
            <a>{{ $val->date }}{{ $val->title }}</a>
            <input type="submit" value="削除する">
        </form>
        <br>
    @endforeach
    <br>
　　<b>投稿済みの記事一覧</b>
    @foreach ($news as $val1)
        <form method="POST" id="form1" action="{{ route('admin.deletemessage') }}">
            @csrf
            <input type="hidden" name="deleteid" value="{{$val1->id}}">
            <!--あえてアンカータグにしている（href入れない）Pタグだと改行される-->
            <a>{{ $val1->date }}{{ $val1->title }}</a>
            <input type="submit" value="削除する">
        </form>
        <br>
    @endforeach
@endsection
