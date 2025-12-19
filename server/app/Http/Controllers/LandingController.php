<?php

namespace App\Http\Controllers;

use App\Actions\Landing\ShowLandingPageAction;
use Illuminate\View\View;

class LandingController extends Controller
{
    public function __invoke(ShowLandingPageAction $action): View
    {
        $data = $action->execute();

        return view('landing', $data);
    }
}

