<?php

namespace App\View\Components;

use App\Http\Controllers\ExpenseController;
use App\Http\Controllers\RevenueController;
use Illuminate\View\Component;

class ExpenseForm extends Component
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

        $validation = \Validator::make(ExpenseController::validationRules(),
            [
                'three_digits.required' => 'Three Digits field is required',
                'three_digits.regex' => 'Must be exactly three digits'
            ]);

        $validation->sometimes(['files'], 'required|mimes:xls,xlsx', function ($input) {
            return $input->expensecategory == 1;
        });

        $validation->sometimes(['sum', 'tag', 'monthpicker2'], 'required', function ($input) {
            return $input->expensecategory == 2;
        });

        $validation->sometimes(['monthpicker3'], 'required', function ($input) {
            return $input->expensecategory == 3;
        });

        $validation->sometimes(['cost-of-good-sold'], 'required', function ($input) {
            return $input->cat3input == 1;
        });

        $validation->sometimes(['affiliate-commission'], 'required|max:100', function ($input) {
            return $input->cat3input == 2;
        });

        $validation->sometimes(['ad-spend-commission'], 'required|max:100', function ($input) {
            return $input->cat3input == 3;
        });

        //expensetype

        $validation->sometimes(['cost-of-good-sold'], 'required', function ($input) {
            return $input->cat3input == 1;
        });


        if ($validation->fails()) {
            return redirect()->back()->withErrors($validation->errors());
        }

        return view('components.expense-form')->with(
            [
                'validator' => $validation,
            ]
        );
    }
}
