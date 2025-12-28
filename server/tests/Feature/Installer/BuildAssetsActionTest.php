<?php

use App\Actions\Install\BuildAssetsAction;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

it('runs build assets action', function () {
    $result = app(BuildAssetsAction::class)->execute();
    expect($result)->toHaveKey('ok');
    expect($result['ok'])->toBeTrue();
});
