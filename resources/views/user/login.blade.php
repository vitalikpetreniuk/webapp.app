@extends('layouts.index-minimal')

@section('forms')

    <form action="" method="POST" class="d-flex justify-content-center align-items-center authorization" id="login-form" method="post">
        @csrf
        <div class="authorization__wrapper">
            <div class="form-group">
                <input type="email" autocomplete="email" placeholder="Your email" name="email" value="{{ old('email') }}">
            </div>
            <div class="form-group">
                <input type="password" autocomplete="password" name="password" placeholder="Your password" value="{{ old('password') }}">
            </div>
            <input class="authorization__submit" type="submit" value="Send">
        </div>
    </form>

    {!! $validator->selector('#login-form') !!}

    <style>
        /*input {*/
        /*    margin-top: 10px;*/
        /*    display: block;*/
        /*    width: 100%;*/
        /*}*/
    </style>
@endsection
