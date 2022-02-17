@extends('layouts.index')

@section('forms')

@endsection

@section('content')
    <section class="section d-flex flex-column">
        <x-top-tabs/>
        <div class="picker-month mt-24">
            <div class="picker-month__year d-flex align-items-center justify-content-between">
                <!--				Если кнопка не активна то добавляем класс inactive-->
                <button class="picker-month__prev">
                    <img src="{{ asset('frontend/images/dist/icons/arrow-picker.svg') }}" alt="arrow-picker">
                </button>
                <span>2021</span>
                <button class="picker-month__next inactive">
                    <img src="{{ asset('frontend/images/dist/icons/arrow-picker.svg')  }}" alt="arrow-picker">
                </button>
            </div>
            <div class="picker-month__months">
                <ul id="listMonths" class="d-flex justify-content-between align-items-center">
                    <!--					Добавляем класс range-active, когда выбираем диапазон-->
                    <li></li>
                    <li></li>
                    <li></li>
                    <li class="range-active"></li>
                    <li class="range-active"></li>
                    <li class="range-active"></li>
                    <li class="range-active"></li>
                    <li></li>
                    <li></li>
                    <li></li>
                    <li></li>
                    <li></li>
                </ul>
            </div>
        </div>
        <div id="chartdiv" class="mt-20"></div>
    </section>

    </div>
    <x-footer/>
@endsection
