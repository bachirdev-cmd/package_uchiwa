<?php

namespace AppDAF\CONFIG;

use AppDAF\CONFIG\INTERFACES\ServiceContainerInterface;
use ReflectionClass;
use ReflectionParameter;

class ReflectionServiceContainer implements ServiceContainerInterface
{
    private array $services = [];
    private array $instances = [];
    private array $bindings = [];

    public function __construct()
    {
        $this->loadServicesFromYaml();
    }

    public function bind(string $interface, string $implementation): void
    {
        $this->bindings[$interface] = $implementation;
    }

    public function get(string $serviceId): object
    {
        if (!$this->has($serviceId)) {
            throw new \InvalidArgumentException("Service '{$serviceId}' not found");
        }

        if (!isset($this->instances[$serviceId])) {
            $className = $this->services[$serviceId];
            $this->instances[$serviceId] = $this->createInstance($className);
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

    private function createInstance(string $className): object
    {
        $reflection = new ReflectionClass($className);

        // Si la classe a une méthode getInstance (Singleton)
        if ($reflection->hasMethod('getInstance')) {
            return $className::getInstance();
        }

        // Récupérer le constructeur
        $constructor = $reflection->getConstructor();

        if (!$constructor) {
            // Pas de constructeur, instanciation simple
            return new $className();
        }

        // Résoudre les dépendances du constructeur
        $dependencies = $this->resolveDependencies($constructor->getParameters());
        
        return $reflection->newInstanceArgs($dependencies);
    }

    private function resolveDependencies(array $parameters): array
    {
        $dependencies = [];

        foreach ($parameters as $parameter) {
            $dependency = $this->resolveDependency($parameter);
            $dependencies[] = $dependency;
        }

        return $dependencies;
    }

    private function resolveDependency(ReflectionParameter $parameter)
    {
        $type = $parameter->getType();

        if (!$type || $type->isBuiltin()) {
            // Type primitif ou pas de type, utiliser valeur par défaut
            if ($parameter->isDefaultValueAvailable()) {
                return $parameter->getDefaultValue();
            }
            throw new \Exception("Cannot resolve primitive parameter: {$parameter->getName()}");
        }

        $typeName = $type->getName();

        // Vérifier s'il y a un binding pour cette interface
        if (isset($this->bindings[$typeName])) {
            $typeName = $this->bindings[$typeName];
        }

        // Instancier récursivement la dépendance
        return $this->createInstance($typeName);
    }

    private function loadServicesFromYaml(): void
    {
        $yamlFile = __DIR__ . '/services.yml';
        if (file_exists($yamlFile)) {
            $this->services = yaml_parse_file($yamlFile);
        }
    }
}
