<?php

namespace App\Http\Traits;

use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

trait AdSpendTrait
{
    /**
     * Получить % of ad spend
     * @return float|int % of ad spend
     */
    private function _getMonthPercentOfAdSpend($from = false, $to = false)
    {
        $from = $from ?: $this->fromstring;
        $to = $to ?: $this->tostring;

        $percent_of_ad_spend = DB::select("SELECT amount FROM $this->expenses_table WHERE type_variable = ? AND date BETWEEN ? AND ?", [3, $from, $to]);
        return isset($percent_of_ad_spend[0]->amount) ? $percent_of_ad_spend[0]->amount / 100 : 0;
    }

    /**
     * Получить % of ad spend за период
     * @return float|int % of ad spend
     */
    public function getMonthPercentOfAdSpend()
    {
        return $this->_getMonthPercentOfAdSpend();
    }
}
