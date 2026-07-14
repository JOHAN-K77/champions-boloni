<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class FinanceController extends Controller
{
    public function index()
    {
        return view('finance.index', [
            'module' => null
        ]);
    }

    public function cash()
    {
        return view('finance.create', [
            'module' => 'finance'
        ]);
    }

    public function payment()
    {
        return view('finance.payment', [
            'module' => 'finance'
        ]);
    }
}