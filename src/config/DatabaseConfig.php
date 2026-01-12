<?php

namespace App\config;

class DatabaseConfig
{
    public static function get(): array
    {
        $env = parse_ini_file(ROOT_DIR . '/.env');
        if ($env === false) {
            throw new \RuntimeException('.env not found');
        }

        return [
            'dsn' => sprintf(
                'pgsql:host=%s;port=%s;dbname=%s',
                $env['DB_HOST'],
                $env['DB_PORT'],
                $env['DB_NAME']
            ),
            'user' => $env['DB_USER'],
            'password' => $env['DB_PASSWORD'],
        ];
    }
}