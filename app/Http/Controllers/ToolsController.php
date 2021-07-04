<?php

namespace App\Http\Controllers;

use App\Models\Document;
use App\Models\SystemLog;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Auth;

class ToolsController extends Controller
{
    //
    public function carRecognition(Request $request)
    {
        $page_title = 'Car Recognition';
        return view('tools.car_recognition', [
            'page_title' => $page_title
        ]);
    }
    public function moneyConversion(Request $request)
    {
        $page_title = 'Money Conversion';
        return view('tools.money_conversion', [
            'page_title' => $page_title
        ]);
    }
    public function distanceConversion(Request $request)
    {
        $page_title = 'Distance Conversion';
        return view('tools.distance_conversion', [
            'page_title' => $page_title
        ]);
    }
    public function vinIdentification(Request $request)
    {
        $page_title = 'VIN Identification';
        return view('tools.vin', [
            'page_title' => $page_title
        ]);
    }
    public function valuation(Request $request)
    {
        $page_title = 'Valuation';
        return view('tools.valuation', [
            'page_title' => $page_title
        ]);
    }
    public function verify_phone(Request $request) {
        $page_title = 'Verify Phone Number';
        return view('tools.verify_phone', compact('page_title'));
    }
    public function mortgage_calculator() {
        $page_title = 'Mortgage Calculator';
        return view('tools.mortgage_calculator', compact('page_title'));
    }
}
