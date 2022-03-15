@extends('layouts.index')

@section('content')

    <section class="section d-flex flex-column">
        <x-reportings-top/>
        <?php  var_dump($data); ?>
        <table class="table mt-20">
            <thead>
            <tr>
                <th>Date</th>
                <th>Summ</th>
                <th>Source</th>
                <th>Tag</th>
                <th>Notes</th>
            </tr>
            </thead>
            <tbody class="editable">
            <tr>
                <td>14.10.2021</td>
                <td class="plus">
                    <span>+$545.00</span>
                    <div class="edit-block d-flex justify-content-center align-items-center">
                        <button class="btn__done d-flex align-items-center justify-content-center">
                            <img src="{{ asset('frontend/images/dist/icons/done.svg') }}" alt="done">
                        </button>
                        <button class="btn__delete d-flex align-items-center justify-content-center">
                            <img src="{{ asset('frontend/images/dist/icons/cross.svg') }}" alt="cross">
                        </button>
                        <button rel="pop-2" class="btn__edit d-flex align-items-center justify-content-center">
                            <img src="{{ asset('frontend/images/dist/icons/pencil.svg') }}" alt="pencil">
                        </button>
                    </div>
                </td>
                <td></td>
                <td></td>
                <td></td>
            </tr>
            </tbody>
        </table>
    </section>

    <x-revenue-form/>
    <x-expense-form/>
    <x-footer/>

@endsection
