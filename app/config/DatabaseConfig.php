<?php

namespace AppDAF\CONFIG;

use AppDAF\CONFIG\INTERFACES\DatabaseConfigInterface;

class DatabaseConfig implements DatabaseConfigInterface
{
    public function getHost(): string
    {
        return $_ENV['DB_HOST'] ?? 'localhost';
    }

    public function getPort(): int
    {
        return (int) ($_ENV['DB_PORT'] ?? 3306);
    }

    public function getDatabase(): string
    {
        return $_ENV['DB_NAME'] ?? $_ENV['POSTGRES_DB'] ?? 'app_daf';
    }

    public function getUsername(): string
    {
        return $_ENV['DB_USER'] ?? $_ENV['POSTGRES_USER'] ?? 'root';
    }

    public function getPassword(): string
    {
        return $_ENV['DB_PASSWORD'] ?? $_ENV['POSTGRES_PASSWORD'] ?? '';
    }

    public function getCharset(): string
    {
        return $_ENV['DB_CHARSET'] ?? 'utf8mb4';
    }
}
