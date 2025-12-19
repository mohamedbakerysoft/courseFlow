<?php

namespace App\Http\Controllers;

use App\Actions\Pages\ShowPageAction;
use Illuminate\View\View;

class PageController extends Controller
{
    public function show(string $slug, ShowPageAction $action): View
    {
        $page = $action->execute($slug);

        return view('pages.show', compact('page'));
    }
}
