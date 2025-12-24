<?php

use App\Actions\Install\WriteEnvAction;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

it('writes env file with provided config', function () {
    $path = storage_path('app/.env-test');
    if (file_exists($path)) {
        @unlink($path);
    }
    $action = app(WriteEnvAction::class);
    $written = $action->execute('https://example.com', [
        'host' => '127.0.0.1',
        'port' => 3306,
        'database' => 'demo',
        'username' => 'demo',
        'password' => 'secret',
    ], $path);
    expect($written)->toBe($path);
    $contents = file_get_contents($path);
    expect($contents)->toContain('APP_DEBUG=false');
    expect($contents)->toContain('APP_URL=https://example.com');
    expect($contents)->toContain('DB_HOST=127.0.0.1');
    expect($contents)->toContain('DB_PORT=3306');
    expect($contents)->toContain('DB_DATABASE=demo');
    expect($contents)->toContain('DB_USERNAME=demo');
    expect($contents)->toContain('DB_PASSWORD=secret');
});
