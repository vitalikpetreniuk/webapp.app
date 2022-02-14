@extends('layouts.index')

@section('forms')

    <form action="{{ route('user.login') }}" class="d-flex flex-column"
          style="max-width: 300px; margin: 0 auto; margin-top: 200px" id="login" method="post">
        @csrf
        <input type="email" value="Ваш email" name="email" value="{{ old('email') }}">
        <input type="password" name="password" value="{{ old('password') }}">
        <input type="submit" value="Отправить">
    </form>

{{--    {!! JsValidator::formRequest('App\Http\Requests\LoginForm') !!}--}}

    <style>
        input {
            margin-top: 10px;
        }
    </style>
@endsection
