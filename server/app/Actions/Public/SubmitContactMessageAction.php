<?php

namespace App\Actions\Public;

use App\Models\User;
use Illuminate\Support\Facades\Mail;

class SubmitContactMessageAction
{
    public function execute(string $name, string $email, string $message, ?string $captchaToken = null): bool
    {
        $enabled = app()->environment('testing') ? false : (bool) (config('services.recaptcha.enabled') ?? false);
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

        $to = $this->resolveRecipientAddress();
        Mail::raw(
            "Contact message\n\nFrom: {$name} <{$email}>\n\n{$message}",
            function ($m) use ($to, $email, $name) {
                $m->to($to)->replyTo($email, $name)->subject('New contact message');
            }
        );

        return true;
    }

    public function resolveRecipientAddress(): string
    {
        $configuredAddress = (string) config('demo.admin_email', User::PROTECTED_ADMIN_EMAIL);

        $admin = User::query()
            ->where('email', $configuredAddress)
            ->first()
            ?: User::query()
                ->where('role', User::ROLE_ADMIN)
                ->orderBy('id')
                ->first();

        if ($admin?->email) {
            return $admin->email;
        }

        return $configuredAddress !== '' ? $configuredAddress : User::PROTECTED_ADMIN_EMAIL;
    }
}
