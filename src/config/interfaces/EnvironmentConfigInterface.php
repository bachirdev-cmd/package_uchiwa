<?php

namespace AppDAF\CONFIG\INTERFACES;

interface EnvironmentConfigInterface
{
    public function get(string $key, $default = null);
    public function has(string $key): bool;
    public function isDevelopment(): bool;
    public function isProduction(): bool;
}
