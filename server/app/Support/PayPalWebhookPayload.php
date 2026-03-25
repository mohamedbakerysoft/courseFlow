<?php

namespace App\Support;

class PayPalWebhookPayload
{
    public function __construct(
        public readonly string $eventType,
        public readonly string $orderId,
    ) {}

    public static function fromArray(array $payload): self
    {
        $eventType = (string) ($payload['type'] ?? $payload['event_type'] ?? '');
        $resource = is_array($payload['resource'] ?? null) ? $payload['resource'] : [];
        $dataObject = is_array($payload['data']['object'] ?? null) ? $payload['data']['object'] : [];

        $orderId = (string) (
            $resource['supplementary_data']['related_ids']['order_id']
            ?? $dataObject['supplementary_data']['related_ids']['order_id']
            ?? $resource['id']
            ?? $dataObject['id']
            ?? ''
        );

        return new self($eventType, $orderId);
    }

    public function shouldCapture(): bool
    {
        return in_array($this->eventType, [
            'PAYMENT.CAPTURE.COMPLETED',
        ], true) && $this->orderId !== '';
    }
}
