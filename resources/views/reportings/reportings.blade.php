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
                <tr @if($item->editable) data-id="{{ $item->id }}" @endif @if(isset($item->from_file)) data-date="{{ $item->from_file }}" @endif data-type="{{ $type }}">
                    <td>{!! $item->type !!}</td>
                    <td class="{{ $item->class }}">
                        <span>{{ $item->amount }}</span>
                        <div class="edit-block d-flex justify-content-center align-items-center">
                            <button class="btn__delete d-flex align-items-center justify-content-center">
                                <img src="{{ asset('frontend/images/dist/icons/cross.svg') }}" alt="cross">
                            </button>
                            @if($item->editable)
                                <button rel="pop-2" class="btn__edit d-flex align-items-center justify-content-center">
                                    <img src="{{ asset('frontend/images/dist/icons/pencil.svg') }}" alt="pencil">
                                </button>
                            @endif
                        </div>
                    </td>
                    <td>@if(isset($item->source))
                            {{ $item->source }}
                        @endif</td>
                    <td>@if(isset($item->tags))
                            {{ $item->tags }}
                        @endif</td>
                    <td>
                        @if(isset($item->comment))
                            <svg width="14" height="14" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path
                                    d="M6.875 7.5a.625.625 0 1 0 0-1.25.625.625 0 0 0 0 1.25ZM10 7.5a.625.625 0 1 0 0-1.25.625.625 0 0 0 0 1.25Zm-6.25 0a.625.625 0 1 0 0-1.25.625.625 0 0 0 0 1.25Z"
                                    fill="#9296A0"></path>
                                <path
                                    d="M6.875 0A6.868 6.868 0 0 0 .964 10.377l-.334 2.67a.625.625 0 0 0 .772.684l2.55-.637a6.787 6.787 0 0 0 2.923.656 6.875 6.875 0 1 0 0-13.75Zm0 12.5a5.558 5.558 0 0 1-2.566-.624.625.625 0 0 0-.438-.05l-1.887.471.247-1.984a.623.623 0 0 0-.093-.414A5.621 5.621 0 1 1 6.875 12.5Z"
                                    fill="#9296A0"></path>
                            </svg>
                            <div class="clue">{{ $item->comment }}</div>
                        @endif
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </section>

    <x-footer/>

@endsection
