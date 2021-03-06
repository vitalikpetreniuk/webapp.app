<?php

namespace App\Http\Traits;

use Illuminate\Support\Facades\DB;

trait AnalyticsTrait
{
    /**
     * Revenue needed спец формула для sweetspot
     * @param float|int $marketing_cost marketing_costs за месяц
     * @return float|int revenue needed
     */
    public function getRevenueNeeded($marketing_cost)
    {
        if ($this->duration > 1) {
            return $this->fixed_costs / (1 - $this->globalcogs - $marketing_cost);
        }

        return $this->fixed_costs / (1 - $this->globalcogs - $marketing_cost);
    }
}
