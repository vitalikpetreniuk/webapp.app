<x-html-top/>
@yield('forms')

@hasSection('content')
    <x-header/>
    <div class="content d-flex">
        <x-nav/>
        @yield('content')
    </div>
    <x-revenue-form/>
    <x-expense-form/>

@endif

@yield('error-content')

<x-close-tags/>
