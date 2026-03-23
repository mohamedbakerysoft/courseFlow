<?php

namespace App\Http\Controllers\Dashboard;

use App\Actions\Dashboard\Finance\GetFinanceStatsAction;
use App\Http\Controllers\Controller;
use Illuminate\View\View;

class FinanceController extends Controller
{
    public function index(GetFinanceStatsAction $stats): View
    {
        $data = $stats->execute();

        return view('dashboard.finance.index', array_merge($data, [
            'financeSection' => 'insights',
        ]));
    }

    public function manualPayments(GetFinanceStatsAction $stats): View
    {
        $data = $stats->execute();

        return view('dashboard.finance.manual-payments', array_merge($data, [
            'financeSection' => 'manual-payments',
        ]));
    }
}
