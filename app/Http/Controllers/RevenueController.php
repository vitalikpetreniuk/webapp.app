<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class RevenueController extends Controller
{

    public function saveFile() {

    }

    public static function validationRules() {
        return [
            'files' => 'required|mimes:xls,xlsx',
        ];
    }
}
