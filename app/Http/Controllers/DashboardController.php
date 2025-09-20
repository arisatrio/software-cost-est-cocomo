<?php

namespace App\Http\Controllers;

use App\Enums\CostDriver;
use App\Enums\ScaleFactor;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    //

    public function index()
    {
        $scaleFactors = ScaleFactor::cases();
        $costDrivers = CostDriver::cases();

        return view('dashboard', compact('scaleFactors', 'costDrivers'));
    }
}
