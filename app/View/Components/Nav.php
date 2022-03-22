<?php

namespace App\View\Components;

use App\Models\Revenue;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\View\Component;

class Nav extends Component
{
    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    public function getNavData() {
        $date = new \DateTime();
        $year = $date->y;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        $from = Carbon::now()->subYear();
        $now = Carbon::now();
        /* Получение сумы растрат */
        $request = "SELECT TO_CHAR(date, 'Month') AS \"month\", EXTRACT(year from date) AS \"YEAR\", SUM(net_sales_amount) as sum FROM revenues WHERE date BETWEEN ? AND ? GROUP BY 1, 2";
//        dd($request);
        $data = DB::select($request, [$from, $now]);

        return view('components.nav', compact('data'));
    }
}
