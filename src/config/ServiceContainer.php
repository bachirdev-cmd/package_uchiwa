<?php

namespace AppDAF\CONFIG;

use AppDAF\CONFIG\INTERFACES\ServiceContainerInterface;

class ServiceContainer implements ServiceContainerInterface
{
    private array $services = [];
    private array $instances = [];

    public function __construct()
    {
        $this->loadServicesFromYaml();
    }

    public function get(string $serviceId): object
    {
        if (!$this->has($serviceId)) {
            throw new \InvalidArgumentException("Service '{$serviceId}' not found");
        }

        if (!isset($this->instances[$serviceId])) {
            $className = $this->services[$serviceId];
            
            // Vérifier si la classe a une méthode getInstance (Singleton pattern)
            if (method_exists($className, 'getInstance')) {
                $this->instances[$serviceId] = $className::getInstance();
            } else {
                // Sinon, instancier normalement
                $this->instances[$serviceId] = new $className();
            }
        }

        return $this->instances[$serviceId];
    }

    public function has(string $serviceId): bool
    {
        return isset($this->services[$serviceId]);
    }

    public function register(string $serviceId, string $className): void
    {
        $this->services[$serviceId] = $className;
    }

    private function loadServicesFromYaml(): void
    {
        $yamlFile = __DIR__ . '/services.yml';
        if (file_exists($yamlFile)) {
            $this->services = yaml_parse_file($yamlFile);
        }
    }
}
