<?php

use App\Actions\Updates\DetectVersionAction;
use App\Models\Setting;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

it('detects no update when versions are equal', function () {
    Setting::updateOrCreate(['key' => 'app.version'], ['value' => '1.0.0']);
    config()->set('app.version', '1.0.0');

    $result = app(DetectVersionAction::class)->execute();

    expect($result['current_version'])->toBe('1.0.0');
    expect($result['new_version'])->toBe('1.0.0');
    expect($result['update_available'])->toBeFalse();
    expect($result['status'])->toBe('no_update');
});

it('detects update when new version is greater', function () {
    Setting::updateOrCreate(['key' => 'app.version'], ['value' => '1.0.0']);
    config()->set('app.version', '1.2.0');

    $result = app(DetectVersionAction::class)->execute();

    expect($result['current_version'])->toBe('1.0.0');
    expect($result['new_version'])->toBe('1.2.0');
    expect($result['update_available'])->toBeTrue();
    expect($result['status'])->toBe('update_available');
});

