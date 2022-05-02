<?php

namespace App\View\Components;

use App\Http\Controllers\ExpenseCalculationsController;
use Carbon\Carbon;
use Illuminate\View\Component;

class Plchart extends Component
{
    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->startDate = isset($_GET['startDate']) ? Carbon::createFromFormat('j M Y', '1 ' . $_GET['startDate'])->firstOfMonth() : Carbon::now()->subMonth()->firstOfMonth();
        $this->endDate = isset($_GET['endDate']) ? Carbon::createFromFormat('j M Y', '1 ' . $_GET['endDate']) : Carbon::now()->lastOfMonth();

        $controller = new ExpenseCalculationsController($this->startDate, $this->endDate);
        $this->fixed_costs = $controller->getFixedExpensesTotalSum();
        $this->globalcogs = $controller->getCogs();
        $this->net_revenue = (int)$controller->getNetRevenueSum();
        $this->marketing_costs = $controller->getMarketingCostsSum();
    }

    /**
     * Точки графика
     * @return false|string json точек графика
     */
    public function chartData()
    {
        $returned = [];

        if (!isset($this->globalcogs) || $this->fixed_costs == 1) return false;

        foreach (range(0.01, 0.42, 0.05) as $marketing_costs) {
            $y = $this->countYFormula($marketing_costs);

            $returned[] = array(
                'x' => round($marketing_costs, 2) ?: 1,
                'y' => (round($y, 2) >= 0) ? round($y, 2) : 1
            );
        }

        return json_encode($returned);
    }

    /**
     * Подсчет y при заданом marketing_costs
     * @param float $marketing_costs траты на маркетинг
     * @return float|int y
     */
    public function countYFormula($marketing_costs)
    {
        return $this->fixed_costs / ((1 - $this->globalcogs) - $marketing_costs);
    }

    /**
     * Положение точки на графике
     * @return array массив x и y точки на графике
     */
    public function currentBullet()
    {
        $returned = [];

        $returned[] = [
            "x" => $this->marketing_costs / $this->net_revenue,
            "y" => $this->net_revenue,
        ];

        return $returned;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        $chart_data = $this->chartData();
        $current_bullet = $this->currentBullet();

        if ($current_bullet[0]['y'] > $this->countYFormula($current_bullet[0]['x'])) {
            $color = '#31DB42';
        } else {
            $color = '#F62A2A';
        }

        return view('components.plchart', [
            'chart_data' => $chart_data,
            'current_bullet' => json_encode($current_bullet),
            'color' => $color,
        ]);
    }
}
