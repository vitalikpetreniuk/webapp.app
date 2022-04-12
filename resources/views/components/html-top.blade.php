<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <!--	<meta name="viewport"-->
    <!--				content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">-->
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="description" content="Webapp">
    <link rel="icon" href="{{ asset('frontend/images/logo.svg') }}">
    <title>index</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    <link rel="stylesheet" href="{{ asset('css/custom.css') }}">
    <link rel="stylesheet" href="{{ asset('frontend/css/inputTags.css') }}">
    <link rel="stylesheet" href="{{ asset('frontend/css/app.min.css') }}">
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    <script type="text/javascript" src="{{ asset('js/app.js')}}"></script>
    <script src="{{ asset('js/jsvalidation.js') }}"></script>
    <script src="https://cdn.amcharts.com/lib/5/index.js"></script>
    <script src="https://cdn.amcharts.com/lib/5/xy.js"></script>
    <script src="https://cdn.amcharts.com/lib/5/themes/Animated.js"></script>
    <script>
        var apivars = {
            expenseurl: "{{ route('expenses.store') }}",
            revenueurl: "{{ route('revenues.store') }}"
        };
    </script>
    {{--    <script src="https://code.jquery.com/jquery-3.6.0.min.js"--}}
{{--            integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>--}}
</head>
<body>
