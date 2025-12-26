<?php

namespace App\Actions\Install;

use Illuminate\Support\Str;

class WriteEnvAction
{
    public function execute(string $appUrl, array $db, ?string $envPath = null): string
    {
        $path = $envPath ?: base_path('.env');
        $appKey = 'base64:'.base64_encode(random_bytes(32));
        $contents = [];
        $contents[] = 'APP_NAME="CourseFlow"';
        $contents[] = 'APP_ENV=production';
        $contents[] = 'APP_KEY='.$appKey;
        $contents[] = 'APP_DEBUG=false';
        $contents[] = 'APP_URL='.$appUrl;
        $contents[] = 'LOG_CHANNEL=stack';
        $contents[] = 'DB_CONNECTION=mysql';
        $contents[] = 'DB_HOST='.$this->val($db['host'] ?? '127.0.0.1');
        $contents[] = 'DB_PORT='.$this->val((string) ($db['port'] ?? '3306'));
        $contents[] = 'DB_DATABASE='.$this->val($db['database'] ?? '');
        $contents[] = 'DB_USERNAME='.$this->val($db['username'] ?? '');
        $contents[] = 'DB_PASSWORD='.$this->val($db['password'] ?? '');
        $contents[] = 'CACHE_STORE=file';
        $contents[] = 'SESSION_DRIVER=file';
        $contents[] = 'QUEUE_CONNECTION=database';
        $payload = implode(PHP_EOL, $contents).PHP_EOL;

        if (file_exists($path)) {
            @copy($path, $path.'.backup');
        }

        file_put_contents($path, $payload);

        return $path;
    }

    protected function val(string $v): string
    {
        return trim($v);
    }
}
