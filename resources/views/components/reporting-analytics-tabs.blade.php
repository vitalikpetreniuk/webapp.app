<div class="tabs-section active">
    @foreach($tabs as $name => $value)
        <a href="{{ $value['url'] }}" class="@if($value['active']) active @endif">{{ $name }}</a>
    @endforeach
</div>
