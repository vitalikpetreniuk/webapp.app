@extends('layouts.index-minimal')

@section('forms')

    <form action="" method="POST" class="d-flex flex-column"
          style="max-width: 300px; margin: 0 auto; margin-top: 200px" id="login-form" method="post">
        @csrf
        <div class="form-group">
            <input type="email" autocomplete="email" placeholder="Your email" name="email" value="{{ old('email') }}">
        </div>
        <div class="form-group">
            <input type="password" autocomplete="password" name="password" placeholder="Your password" value="{{ old('password') }}">
        </div>
        <input type="submit" value="Send">
    </form>

    {!! $validator->selector('#login-form') !!}

    <style>
        input {
            margin-top: 10px;
            display: block;
            width: 100%;
        }
    </style>
@endsection
