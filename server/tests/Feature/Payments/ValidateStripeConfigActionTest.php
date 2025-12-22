<?php

use App\Actions\Payments\ValidateStripeConfigAction;

it('fails when keys have wrong format', function () {
    $action = app(ValidateStripeConfigAction::class);
    $result = $action->execute('foo', 'bar', 'whsec_test');
    expect($result['valid'])->toBeFalse();
    expect($result['message'])->toContain('publishable key');
    expect($result['message'])->toContain('secret key');
});

it('fails with stripe api error when prefixes are valid but secret is invalid', function () {
    $action = app(ValidateStripeConfigAction::class);
    $result = $action->execute('pk_test_123', 'sk_test_invalid', 'whsec_test');
    expect($result['valid'])->toBeFalse();
    expect($result['message'])->toContain('Stripe API error');
});
