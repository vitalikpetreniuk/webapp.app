<?php

namespace App\Http\Traits;

use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

trait RevenueTrait
{
    /**
     * Считает net_revenue за период
     * @return int|float - revenue
     */
    private function _getNetRevenue($from = false, $to = false)
    {
        $from = $from ?: $this->fromstring;
        $to = $to ?: $this->tostring;

        $amount = DB::select("SELECT SUM(amount) as amount FROM $this->revenues_table WHERE date BETWEEN ? AND ?", [$from, $to]);

        return isset($amount[0]->amount) ? (int) $amount[0]->amount : 0;
    }

    /**
     * Считает net_revenue за период (сума)
     * @return int|float - revenue
     */
    public function getNetRevenueSum()
    {
        return $this->_getNetRevenue($this->fromstring, $this->tostring);
    }
}
