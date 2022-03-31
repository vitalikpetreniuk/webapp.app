@extends('layouts.index')

@section('content')
    <section class="section d-flex flex-column">
       <x-reporting-analytics-tabs/>
        <div class="type-analytics mt-20">
            <ul class="d-flex">
                <li>
                    <a href="#">P/L Corve model</a>
                </li>
                <li>
                    <a href="sweetspot.html">Sweetspot Analytics</a>
                </li>
                <li>
                    <a href="special.html">Specid event Analytics</a>
                </li>
            </ul>
        </div>
        <div class="datepicker d-flex mt-20">
            <input id="datepicker"/>
            <div class="datepicker__icon d-flex align-items-center justify-content-center">
                <img src="{{ asset('frontend/images/dist/icons/calendar.svg') }}" alt="">
            </div>
        </div>
        <div class="tabs-section alt active mt-24">
            <button>Data range</button>
            <button class="active">Month range</button>
        </div>
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
                    <li class="range-active start"></li>
                    <li class="range-active"></li>
                    <li class="range-active"></li>
                    <li class="range-active end"></li>
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

    <x-revenue-form/>
    <x-expense-form/>
    <x-footer/>
@endsection
