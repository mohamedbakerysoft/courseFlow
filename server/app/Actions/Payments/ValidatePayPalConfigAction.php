<?php
declare(strict_types=1);

namespace App\Actions\Payments;

class ValidatePayPalConfigAction
{
    public function execute(string $clientId, string $secret, string $mode): array
    {
        $clientId = trim($clientId);
        $secret = trim($secret);
        $mode = trim(strtolower($mode));

        if ($clientId === '' || $secret === '') {
            return [
                'valid' => false,
                'message' => 'Client ID and secret must not be empty.',
            ];
        }

        if (! in_array($mode, ['sandbox', 'live'], true)) {
            return [
                'valid' => false,
                'message' => 'Mode must be either "sandbox" or "live".',
            ];
        }

        return [
            'valid' => true,
            'message' => null,
        ];
    }
}
