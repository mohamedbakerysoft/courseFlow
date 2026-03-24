<?php

namespace App\Http\Controllers;

use App\Actions\Faqs\ShowFaqPageAction;
use Illuminate\View\View;

class FaqController extends Controller
{
    public function index(ShowFaqPageAction $action): View
    {
        return view('faq.index', $action->execute());
    }
}
