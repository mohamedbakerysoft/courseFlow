<?php

use App\Actions\Security\ValidateRecaptchaAction;

it('returns true when recaptcha disabled', function () {
    config()->set('services.recaptcha.enabled', false);
    $ok = app(ValidateRecaptchaAction::class)->execute('test-token');
    expect($ok)->toBeTrue();
});

it('returns true in testing env even if enabled', function () {
    config()->set('services.recaptcha.enabled', true);
    config()->set('services.recaptcha.secret_key', 'test-secret');
    $ok = app(ValidateRecaptchaAction::class)->execute('test-token');
    expect($ok)->toBeTrue();
});

it('handles null token gracefully in testing env', function () {
    config()->set('services.recaptcha.enabled', true);
    config()->set('services.recaptcha.secret_key', 'test-secret');
    $ok = app(ValidateRecaptchaAction::class)->execute(null);
    expect($ok)->toBeTrue();
});
