<header class="header d-flex">
    <a href="/" class="header__logo d-flex align-items-center">
        <img src="{{ asset('frontend/images/logo.svg') }}" alt="logo">
        <span>Valuable Analytics</span>
    </a>
    <div class="header__buttons d-flex">
        <button id="revenue" class="btn__header green d-flex align-items-center">
            <img src="{{ asset('frontend/images/dist/icons/plus.svg') }}" alt="plus">
            <span>Revenue</span>
        </button>
        <button id="expenses" class="btn__header red d-flex align-items-center">
            <img src="{{ asset('frontend/images/dist/icons/minus.svg') }}" alt="minus">
            <span>Expenses</span>
        </button>
    </div>
</header>
