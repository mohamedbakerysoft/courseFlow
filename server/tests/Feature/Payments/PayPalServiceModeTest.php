<?php

use App\Services\PayPalService;

it('does not use mocked paypal gateway in production just because demo content is enabled', function () {
    config()->set('demo.enabled', true);
    app()->detectEnvironment(fn () => 'production');

    $service = new class(app(\App\Services\SettingsService::class)) extends PayPalService
    {
        public function usesMockGateway(): bool
        {
            return $this->shouldMockGateway();
        }
    };

    expect($service->usesMockGateway())->toBeFalse();
});
