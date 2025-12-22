<?php
declare(strict_types=1);

namespace App\Actions\Payments;

use Stripe\Account;
use Stripe\Stripe;

class ValidateStripeConfigAction
{
    public function execute(string $publishableKey, string $secretKey, string $webhookSecret): array
    {
        $publishableKey = trim($publishableKey);
        $secretKey = trim($secretKey);
        $webhookSecret = trim($webhookSecret);

        $errors = [];
        if (! str_starts_with($publishableKey, 'pk_')) {
            $errors[] = 'Invalid publishable key format. It must start with "pk_".';
        }
        if (! str_starts_with($secretKey, 'sk_')) {
            $errors[] = 'Invalid secret key format. It must start with "sk_".';
        }
        if (! empty($errors)) {
            return [
                'valid' => false,
                'message' => implode(' ', $errors),
            ];
        }

        try {
            Stripe::setApiKey($secretKey);
            Account::retrieve();
        } catch (\Throwable $e) {
            return [
                'valid' => false,
                'message' => 'Stripe API error: '.$e->getMessage(),
            ];
        }

        return [
            'valid' => true,
            'message' => null,
        ];
    }
}
