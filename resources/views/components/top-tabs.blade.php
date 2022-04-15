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
