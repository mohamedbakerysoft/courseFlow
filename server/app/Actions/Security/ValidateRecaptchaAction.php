<?php

namespace App\Actions\Security;

class ValidateRecaptchaAction
{
    public function execute(?string $captchaToken = null): bool
    {
        $enabled = app()->environment(['testing', 'dusk', 'dusk.local']) ? false : (bool) (config('services.recaptcha.enabled') ?? false);
        $secret = (string) (config('services.recaptcha.secret_key') ?? '');

        if ($enabled && $secret !== '') {
            $postData = http_build_query([
                'secret' => $secret,
                'response' => $captchaToken ?? '',
            ]);
            $context = stream_context_create([
                'http' => [
                    'method' => 'POST',
                    'header' => "Content-type: application/x-www-form-urlencoded\r\n",
                    'content' => $postData,
                    'timeout' => 5,
                ],
            ]);
            $response = @file_get_contents('https://www.google.com/recaptcha/api/siteverify', false, $context);
            $payload = is_string($response) ? json_decode($response, true) : null;
            $ok = is_array($payload) ? (bool) ($payload['success'] ?? false) : false;
            if (! $ok) {
                return false;
            }
        }

        return true;
    }
}
