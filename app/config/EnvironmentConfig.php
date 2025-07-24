<?php

namespace AppDAF\CONFIG;

use AppDAF\CONFIG\INTERFACES\EnvironmentConfigInterface;

class EnvironmentConfig implements EnvironmentConfigInterface
{
    public function __construct()
    {
        // Charger le .env seulement si on n'est pas dans Docker
        if (!isset($_ENV['DOCKER_ENV']) && !isset($_ENV['APP_ENV'])) {
            require_once __DIR__ . '/env.php';
        }
    }

    public function get(string $key, $default = null)
    {
        return $_ENV[$key] ?? $default;
    }

    public function has(string $key): bool
    {
        return isset($_ENV[$key]);
    }

    public function isDevelopment(): bool
    {
        return $this->get('APP_ENV', 'development') === 'development';
    }

    public function isProduction(): bool
    {
        return $this->get('APP_ENV') === 'production';
    }
}
