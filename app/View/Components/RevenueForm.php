<?php

namespace App\View\Components;

use App\Http\Controllers\RevenueController;
use Illuminate\View\Component;

class RevenueForm extends Component
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

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        $validator = \JsValidator::make(RevenueController::validationRules());

        return view('components.revenue-form')->with(
            [
                'validator' => $validator
            ]
        );
    }
}
