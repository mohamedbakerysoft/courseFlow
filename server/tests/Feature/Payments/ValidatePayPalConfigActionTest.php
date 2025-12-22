<?php

use App\Actions\Payments\ValidatePayPalConfigAction;

it('fails when credentials are empty', function () {
    $action = app(ValidatePayPalConfigAction::class);
    $result = $action->execute('', '', 'sandbox');
    expect($result['valid'])->toBeFalse();
    expect($result['message'])->toContain('must not be empty');
});

it('fails when mode is invalid', function () {
    $action = app(ValidatePayPalConfigAction::class);
    $result = $action->execute('client', 'secret', 'dev');
    expect($result['valid'])->toBeFalse();
    expect($result['message'])->toContain('Mode');
});

it('passes for sandbox and live with non-empty credentials', function () {
    $action = app(ValidatePayPalConfigAction::class);
    $sandbox = $action->execute('client', 'secret', 'sandbox');
    $live = $action->execute('client', 'secret', 'live');
    expect($sandbox['valid'])->toBeTrue();
    expect($sandbox['message'])->toBeNull();
    expect($live['valid'])->toBeTrue();
    expect($live['message'])->toBeNull();
});
