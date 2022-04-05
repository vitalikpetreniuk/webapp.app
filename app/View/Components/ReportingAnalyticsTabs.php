<?php

namespace App\View\Components;

use Illuminate\Support\Facades\Route;
use Illuminate\View\Component;

class ReportingAnalyticsTabs extends Component
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

    public function tabsData()
    {
        $name = Route::currentRouteName();

        $tabs = [
            'Reporting' => [
                'url' => route('home'),
                'active' => $name == 'home' ?: false,
            ],
            'Analytics' => [
                'url' => route('analytics'),
                'active' => $name == 'analytics' ?: false,
            ],
        ];

        return $tabs;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        return view('components.reporting-analytics-tabs')->with(['tabs' => $this->tabsData()]);
    }
}
