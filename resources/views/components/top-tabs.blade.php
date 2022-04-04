<x-reporting-analytics-tabs />
<div class="type-analytics mt-20">
    <ul class="d-flex">
        @foreach($tabs as $name => $value)
        <li class="@if($value['active']) active @endif">
            <a href="{{ $value['url'] }}">{{ $name }}</a>
        </li>
        @endforeach
    </ul>
</div>
<div class="datepicker d-flex mt-20">
    <input id="datepicker"/>
    <div class="datepicker__icon d-flex align-items-center justify-content-center">
        <img src="{{ asset('frontend/images/dist/icons/calendar.svg') }}" alt="">
    </div>
</div>
