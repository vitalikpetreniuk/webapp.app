@extends('layouts.index')

@section('error-content')
    <div class="notfound d-flex align-items-center justify-content-center flex-column">
        <span>404</span>
        <p>Ooops!... Page not found</p>
        <a href="/" id="revenue" class="btn__header green d-flex align-items-center">
            <span>Main Page</span>
        </a>
    </div>
@endsection
