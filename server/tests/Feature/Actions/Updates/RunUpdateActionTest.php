<?php

use App\Actions\Updates\RunUpdateAction;
use App\Models\Setting;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

it('runs update and stores new version', function () {
    Setting::updateOrCreate(['key' => 'app.version'], ['value' => '1.0.0']);
    config()->set('app.version', '1.1.0');

    $result = app(RunUpdateAction::class)->execute();

    expect($result['ok'])->toBeTrue();
    expect($result['new_version'])->toBe('1.1.0');
    $stored = Setting::where('key', 'app.version')->first();
    expect($stored)->not->toBeNull();
    expect($stored->value)->toBe('1.1.0');
});

