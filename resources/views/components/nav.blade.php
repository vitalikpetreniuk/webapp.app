<nav class="nav">
    <div class="mounthly-calc d-flex flex-column">
        <span>Net profit calculator</span>
        <div class="mounthly-calc__list">
            @foreach($data as $item)
            <div class="title">
                <span>{{ $item->month }}</span>
            </div>
            <ul class="inner">
                <li class="d-flex align-items-center justify-content-between">
                    <span>Net revenue</span>
                    <span>$<span>{{ number_format($item->sum, 2, '.', ',') }}</span></span>
                </li>
                <li class="d-flex align-items-center justify-content-between">
                    <span>Total marketing costs</span>
                    <span>$<span>{{ number_format($item->total_marketing_costs, 2, '.', ',') }}</span></span>
                </li>
                <li class="d-flex align-items-center justify-content-between">
                    <span>Net profit/loss</span>
                    <span><?php if ($item->net_profit < 0) echo '-' ?>$<span>{{ number_format(abs($item->net_profit), 2, '.', ',') }}</span></span>
                </li>
            </ul>
            @endforeach
        </div>
    </div>
</nav>
