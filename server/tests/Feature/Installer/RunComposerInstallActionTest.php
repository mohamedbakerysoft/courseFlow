<?php

use App\Actions\Install\RunComposerInstallAction;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

it('runs composer install action', function () {
    $result = app(RunComposerInstallAction::class)->execute();
    expect($result)->toHaveKey('ok');
    expect($result['ok'])->toBeTrue();
});
