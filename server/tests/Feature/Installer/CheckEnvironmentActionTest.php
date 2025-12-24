<?php

use App\Actions\Install\CheckEnvironmentAction;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

it('checks environment and returns status', function () {
    $result = app(CheckEnvironmentAction::class)->execute();
    expect($result)->toHaveKey('ok');
    expect($result)->toHaveKey('php_version');
    expect($result)->toHaveKey('missing_extensions');
    expect($result)->toHaveKey('not_writable');
    expect($result['php_version'])->toBeString();
});
