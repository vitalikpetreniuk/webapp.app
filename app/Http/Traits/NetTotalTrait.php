<?php

namespace App\Http\Traits;

use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

trait NetTotalTrait
{
    /**
     * Считает суму net_profit
     * @param $net_revenue - net_revenue
     * @param $marketing_costs - расходы на рекламу
     * @return float|int - число-сума
     */
    public function countMonthNetTotal($net_revenue, $marketing_costs)
    {
        if (!$marketing_costs) return 0;
        if (in_array(1, [$this->getFixedExpensesTotal(), $this->getFixedExpensesTotal(), $this->getCogs(), $marketing_costs], true)) return 0;

        $ad_spend_commission = $this->getMonthPercentOfAdSpend() + 1;
        return $net_revenue * (1 - $this->getMonthAffiliateCost()) - $this->getFixedExpensesTotal() - ($this->getCogs() * $net_revenue) - $this->getAdSpend() * $ad_spend_commission;
    }

}
