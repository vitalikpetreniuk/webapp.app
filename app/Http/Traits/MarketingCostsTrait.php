<?php

namespace App\Http\Traits;

use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

trait MarketingCostsTrait
{
    /**
     * Возращает расходы на маркетинг
     * type_of_sum = 1 -fixed costs
     * type_of_sum = 2 - ad_spend costs
     * @return float|int - расходы на маркетинг
     */
    private function _getMarketingCosts($from = false, $to = false)
    {
        $from = $from ?: $this->fromstring;
        $to = $to ?: $this->tostring;

        // Получение ad_spend числа за месяц
        $ad_spend = DB::select("SELECT sum(amount) as amount FROM $this->expenses_table WHERE type_of_sum = ? AND date BETWEEN ? AND ?", [2, $from, $to]);

        return isset($ad_spend[0]->amount) ? (int)$ad_spend[0]->amount : 0;
    }

    /**
     * Возращает расходы на маркетинг (среднее)
     * @return float|int - расходы на маркетинг
     */
    public function getMarketingCosts()
    {
        return $this->loopSumAverage('_getMarketingCosts');
    }

    /**
     * Возращает расходы на маркетинг суму)
     * @return float|int - расходы на маркетинг
     */
    public function getMarketingCostsSum()
    {
        return $this->_getMarketingCosts($this->fromstring, $this->tostring);
    }
}
