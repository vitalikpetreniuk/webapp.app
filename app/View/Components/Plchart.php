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

        if ($this->endDate->year - $this->startDate->year == 0) {
            $this->duration = $this->endDate->month - $this->startDate->month + 1 ?: 1;
        } else {
            $this->duration = ($this->endDate->month - $this->startDate->month <= 1 ? $this->endDate->month - $this->startDate->month + 1 : 1) + ($this->endDate->year - $this->startDate->year) * 12;
        }

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
    public function currentBullet(): array
    {
        $returned = [];

        $returned[] = [
            "x" => $this->marketing_costs / $this->net_revenue,
            "y" => $this->net_revenue,
        ];

        return $returned;
    }

    /**
     * Форматирование даты для отображения в легенде
     * @return string дата строкой
     */
    private function chartDate(): string
    {
        if ($this->duration > 1) {
            return $this->startDate->format('M Y').' - '. $this->endDate->format('M Y');
        }else {
            return $this->startDate->format('M Y');
        }
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
        $date_period = $this->chartDate();

        if ($current_bullet[0]['y'] > $this->countYFormula($current_bullet[0]['x'])) {
            $color = 'green';
            $hex = '#31DB42';
        } else {
            $color = '#red';
            $hex = '#F62A2A';
        }

        $data = [];

        if ($chart_data) {
            $data = [
                'chart_data' => $chart_data,
                'current_bullet' => $current_bullet,
                'color' => $color,
                'hex' => $hex,
                'date_period' => $date_period,
            ];
        }

        return view('components.plchart', $data);
    }
}
