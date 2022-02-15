<?php

namespace App\View\Components;

use \Illuminate\Support\Facades\Request;
use Illuminate\View\Component;

use JsValidator;
use Validator;

use App\Http\Controllers\RevenueController;

class RevenueExpensesModals extends Component
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

        $validator = JsValidator::make(RevenueController::validationRules());

        return view('components.revenue-expenses-modals')->with([
            'validator' => $validator,
        ]);
    }

    public function store(Request $request) {
        $validation = Validator::make($request->all(), RevenueController::validationRules());

        if ($validation->fails()) {
            return redirect()->back()->withErrors($validation->errors());
        }
    }
}
