<x-html-top/>
@yield('forms')

@hasSection('content')
    <x-header/>
    <div class="content d-flex">
        <x-nav/>
        @yield('content')
    </div>
    <x-revenue-expenses-modals/>
@endif

@yield('error-content')

<x-close-tags/>
