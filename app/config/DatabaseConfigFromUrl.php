<?php

namespace AppDAF\CONFIG;

use AppDAF\CONFIG\INTERFACES\DatabaseConfigInterface;

class DatabaseConfigFromUrl implements DatabaseConfigInterface
{
    private array $config;

    public function __construct()
    {
        $databaseUrl = $_ENV['DATABASE_URL'] ?? $_ENV['DATABASE_PUBLIC_URL'] ?? '';
        
        if ($databaseUrl) {
            $this->config = $this->parseUrl($databaseUrl);
        } else {
            // Fallback vers les variables individuelles
            $this->config = [
                'host' => $_ENV['DB_HOST'] ?? 'localhost',
                'port' => (int)($_ENV['DB_PORT'] ?? 5432),
                'database' => $_ENV['DB_NAME'] ?? 'app_daf',
                'username' => $_ENV['DB_USER'] ?? 'postgres',
                'password' => $_ENV['DB_PASSWORD'] ?? '',
            ];
        }
    }

    private function parseUrl(string $url): array
    {
        $parsed = parse_url($url);
        
        return [
            'host' => $parsed['host'],
            'port' => $parsed['port'] ?? 5432,
            'database' => ltrim($parsed['path'], '/'),
            'username' => $parsed['user'],
            'password' => $parsed['pass'],
        ];
    }

    public function getHost(): string
    {
        return $this->config['host'];
    }

    public function getPort(): int
    {
        return $this->config['port'];
    }

    public function getDatabase(): string
    {
        return $this->config['database'];
    }

    public function getUsername(): string
    {
        return $this->config['username'];
    }

    public function getPassword(): string
    {
        return $this->config['password'];
    }

    public function getCharset(): string
    {
        return 'utf8mb4';
    }
}
