<?php

namespace App\Http\Controllers;

use App\Actions\Public\SubmitContactMessageAction;
use App\Http\Requests\Public\ContactRequest;
use Illuminate\Http\RedirectResponse;

class ContactController extends Controller
{
    public function store(ContactRequest $request, SubmitContactMessageAction $submit): RedirectResponse
    {
        $data = $request->validated();
        $ok = $submit->execute(
            (string) $data['name'],
            (string) $data['email'],
            (string) $data['message'],
            (string) ($data['captcha_token'] ?? '')
        );

        if (! $ok) {
            return back()->withErrors(['captcha' => __('Captcha verification failed')])->withInput();
        }

        return back()->with('status', __('Message sent'));
    }
}
