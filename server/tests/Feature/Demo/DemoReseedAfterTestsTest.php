<?php

namespace Tests\Feature\Demo;

use App\Models\Course;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class DemoReseedAfterTestsTest extends TestCase
{
    public function test_demo_database_reseed_command_populates_demo_courses(): void
    {
        $originalDefault = Config::get('database.default');
        $originalSqliteDatabase = Config::get('database.connections.sqlite.database');
        $originalEnv = env('APP_ENV');

        $dbPath = database_path('database.sqlite');
        if (file_exists($dbPath)) {
            unlink($dbPath);
        }

        Config::set('database.default', 'sqlite');
        Config::set('database.connections.sqlite.database', $dbPath);
        Config::set('demo.enabled', true);
        Config::set('app.env', 'demo');
        putenv('APP_ENV=demo');

        DB::purge('sqlite');
        DB::reconnect('sqlite');

        Artisan::call('demo:reseed-after-tests', ['--force-testing' => true]);

        $this->assertFileExists($dbPath);

        $count = Course::on('sqlite')->count();
        $this->assertGreaterThan(0, $count);

        Config::set('database.default', $originalDefault);
        Config::set('database.connections.sqlite.database', $originalSqliteDatabase);
        if ($originalEnv !== false) {
            Config::set('app.env', $originalEnv);
            putenv('APP_ENV='.$originalEnv);
        }

        DB::purge('sqlite');
        DB::reconnect('sqlite');
    }
}
