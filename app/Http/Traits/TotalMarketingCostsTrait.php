<?php

namespace App\Http\Traits;

use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

trait TotalMarketingCostsTrait
{
    /**
     * Подсчёт сумы расходов на маркетинг
     * @return float|int - сума расходов на маркетинг
     */
    private function _countTotalMarketingCosts($from = false, $to = false)
    {
        $ad_spend = $this->getMarketingCosts();

        $percent_of_ad_spend = $this->getMonthPercentOfAdSpend();
        $percent_of_revenue = $this->getMonthPercentOfRevenue();

        return $ad_spend + ($this->getNetRevenueSum() * $percent_of_revenue) + ($ad_spend * $percent_of_ad_spend);
    }

    /**
     * Подсчёт сумы расходов на маркетинг за период (среднее)
     * @return float|int - сума расходов на маркетинг
     */
    public function countTotalMarketingCosts()
    {
        return $this->loopSumAverage('_countTotalMarketingCosts');
    }

    /**
     * Подсчёт ad_spend за период
     * @return float|int сума ad_spend
     */
    public function getAdSpend() {
        $ad_spend = DB::select("SELECT sum(amount) as amount FROM $this->expenses_table WHERE from_file = true AND date BETWEEN ? AND ?", [$this->fromstring, $this->tostring]);

        return isset($ad_spend[0]->amount) ? $ad_spend[0]->amount : 0;
    }

    /**
     * Получить % of net revenue за период
     * @return float|int % of net revenue
     */
    public function getMonthPercentOfRevenue()
    {
        return $this->_getMonthPercentOfRevenue();
    }

    /**
     * Получить % of net revenue
     * @return float|int % of net revenue
     */
    private function _getMonthPercentOfRevenue($from = false, $to = false)
    {
        $from = $from ?: $this->fromstring;
        $to = $to ?: $this->tostring;

        $percent_of_revenue = DB::select("SELECT amount FROM $this->expenses_table WHERE type_variable = ? AND date BETWEEN ? AND ?", [2, $from, $to]);
        if (!isset($percent_of_revenue[0])) return 0;
        if ($percent_of_revenue[0]->amount >= 1) {
            return $percent_of_revenue[0]->amount / 100;
        } else {
            return $percent_of_revenue[0]->amount / 10;
        }
    }

    /**
     * Синоним к % of net revenue
     * @return float|int - % of net revenue
     */
    public function getMonthAffiliateCost()
    {
        return $this->getMonthPercentOfRevenue();
    }
}
