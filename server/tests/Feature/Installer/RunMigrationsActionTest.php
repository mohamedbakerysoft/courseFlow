<?php

use App\Actions\Install\RunMigrationsAction;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

it('runs migrations successfully', function () {
    $result = app(RunMigrationsAction::class)->execute();
    expect($result)->toHaveKey('ok');
    expect($result['ok'])->toBeTrue();
});
