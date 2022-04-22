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
        $startDate = isset($_GET['startDate']) ? Carbon::createFromFormat('M Y', $_GET['startDate'])->firstOfMonth() : Carbon::now()->subMonth()->firstOfMonth();
        $endDate = isset($_GET['endDate']) ? Carbon::createFromFormat('M Y', $_GET['endDate']) : Carbon::now()->subMonth()->lastOfMonth();
        $this->startDate = $startDate;
        $this->endDate = $endDate;

        $controller = new ExpenseCalculationsController($this->startDate, $this->endDate);
        $this->fixed_costs = $controller->getFixedExpensesTotal();
        $this->globalcogs = $controller->getCogs();
        $this->net_revenue = (int)$controller->getNetRevenue();
        $this->marketing_costs = $controller->getMarketingCosts();
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
            $y = $this->fixed_costs / ((1 - $this->globalcogs) - $marketing_costs);

            $returned[] = array(
                'x' => round($marketing_costs, 2) ?: 1,
                'y' => (round($y, 2) >= 0) ? round($y, 2) : 1
            );
        }

        return json_encode($returned);
    }

    /**
     * Положение точки на графике
     * @return false|string json точки на графике
     */
    public function currentBullet()
    {
        $returned = [];

        $returned[] = [
            "x" => $this->marketing_costs / $this->net_revenue,
            "y" => $this->net_revenue,
        ];

        return json_encode($returned);
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
        return view('components.plchart', compact('chart_data', 'current_bullet'));
    }
}