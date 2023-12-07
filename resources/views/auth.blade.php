@extends('layout.helloapp')

@section('title','ユーザー認証')

@section('member')
  @perent
  ユーザー認証ページ
@endsectipn

@section('content')
<p>{{ $message}}</p>
  <form acrion="/hello/auth" method="post">
    <table>
      @csrf
      <tr>
        <th>mail: </th><td><input type="text" name="email"></td>
      </tr>
      <tr>
        <th>pass: </th><td><input type="password" name="password"></td>
      </tr>
      <tr>
        <th></th><td><input type="submit" value="send"></td>
      </tr>
    </teble>
  </form>
  @endsection