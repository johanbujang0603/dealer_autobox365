<?php

namespace App\Http\Controllers\Dealer;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class DashboardController extends Controller
{
    //
    public function index (){
        return view('dealer.dashboard.index');
    }
}
