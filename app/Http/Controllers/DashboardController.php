<?php

namespace App\Http\Controllers;

use App\Actions\Dashboard\ComputeDashboardDataAction;

class DashboardController extends Controller
{
    public function index()
    {
        $data = (new ComputeDashboardDataAction)();

        return view('dashboard', $data);
    }
}
