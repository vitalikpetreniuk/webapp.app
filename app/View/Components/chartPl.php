<?php

namespace App\View\Components;

use App\Http\Controllers\ExpenseCalculationsController;
use App\Http\Controllers\ExpenseController;
use Carbon\Carbon;
use Illuminate\View\Component;

class chartPl extends Component
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

    protected function getController()
    {
        return new ExpenseCalculationsController(Carbon::createFromFormat('Y-m-d', '2022-01-01'), Carbon::createFromFormat('Y-m-d', '2022-31-01'));
    }

    public function chartData($controller)
    {
        $fixed_costs = $controller->getFixedExpensesTotal();
        $globalcogs = $controller->getCogs() / 100;

        $returned = [];

        foreach (range(0.01, 0.42, 0.05) as $cogs) {
            $y = $fixed_costs / ((1 - $globalcogs) - $cogs);
            $returned[] = array(
                'x' => round($cogs, 2),
                'y' => round($y, 2)
            );
        }

        return json_encode($returned);
    }

    public function currentBullet($controller)
    {
        $returned = [];

        $returned[] = [
            "x" => $controller->getCogs() / 100,
            "y" => $controller->getFixedExpensesTotal(),
        ];

        return json_encode($returned);
    }

    public function getData()
    {
        $controller = $this->getController();

        return [
            'current_bullet' => $this->currentBullet($controller),
            'chart_data' => $this->chartData($controller),
        ];
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        return view('components.chart-pl', $this->getData());
    }
}
