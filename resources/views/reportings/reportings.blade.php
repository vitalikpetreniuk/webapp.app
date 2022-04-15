@extends('layouts.index')

@section('content')
    <section class="section d-flex flex-column">
        <x-reportings-top/>
        <table id="analytics-table" class="table mt-20">
            <thead>
            <tr>
                <th>Type</th>
                <th>Summ</th>
                <th>Source</th>
                <th>Tag</th>
                <th>Notes</th>
            </tr>
            </thead>
            <tbody class="editable">
            <?php //dd($data) ?>
            @foreach($data as $item)
                @php
                    $type = $item->class == 'plus' ? 'revenue' : 'expense'
                @endphp
                <tr @if($item->editable) data-id="{{ $item->id }}" @endif data-type="{{ $type }}">
                    <td>{{ $item->type }}</td>
                    <td class="{{ $item->class }}">
                        <span>{{ $item->amount }}</span>
                        @if($item->editable)
                            <div class="edit-block d-flex justify-content-center align-items-center">
                                @if(false)
                                    <button class="btn__done d-flex align-items-center justify-content-center">
                                        <img src="{{ asset('frontend/images/dist/icons/done.svg') }}" alt="done">
                                    </button>
                                @endif
                                <button class="btn__delete d-flex align-items-center justify-content-center">
                                    <img src="{{ asset('frontend/images/dist/icons/cross.svg') }}" alt="cross">
                                </button>
                                <button rel="pop-2" class="btn__edit d-flex align-items-center justify-content-center">
                                    <img src="{{ asset('frontend/images/dist/icons/pencil.svg') }}" alt="pencil">
                                </button>
                            </div>
                        @endif
                    </td>
                    <td>@if(isset($item->source)){{ $item->source }}@endif</td>
                    <td></td>
                    <td></td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </section>

    <x-footer/>

@endsection
