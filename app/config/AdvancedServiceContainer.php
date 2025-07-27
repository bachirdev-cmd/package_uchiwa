<?php

namespace AppDAF\CONFIG;

use AppDAF\CONFIG\INTERFACES\ServiceContainerInterface;
use ReflectionClass;

class AdvancedServiceContainer implements ServiceContainerInterface
{
    private array $services = [];
    private array $instances = [];
    private array $definitions = [];

    public function __construct()
    {
        $this->loadServicesFromYaml();
    }

    public function get(string $serviceId): object
    {
        if (!$this->has($serviceId)) {
            throw new \InvalidArgumentException("Service '{$serviceId}' not found");
        }

        // Si déjà instancié, retourner l'instance (singleton par défaut)
        if (isset($this->instances[$serviceId])) {
            return $this->instances[$serviceId];
        }

        // Créer l'instance avec injection de dépendances
        $this->instances[$serviceId] = $this->createService($serviceId);
        
        return $this->instances[$serviceId];
    }

    public function has(string $serviceId): bool
    {
        return isset($this->definitions[$serviceId]);
    }

    public function register(string $serviceId, string $className): void
    {
        $this->definitions[$serviceId] = [
            'class' => $className,
            'arguments' => []
        ];
    }

    private function createService(string $serviceId): object
    {
        $definition = $this->definitions[$serviceId];
        $className = $definition['class'];
        $arguments = $definition['arguments'] ?? [];

        // Résoudre les arguments
        $resolvedArguments = $this->resolveArguments($arguments);

        $reflection = new ReflectionClass($className);

        // Vérifier si la classe a une méthode getInstance (Singleton pattern)
        if ($reflection->hasMethod('getInstance')) {
            return $className::getInstance();
        }

        // Créer l'instance avec les arguments résolus
        if (empty($resolvedArguments)) {
            return new $className();
        }

        return $reflection->newInstanceArgs($resolvedArguments);
    }

    private function resolveArguments(array $arguments): array
    {
        $resolved = [];

        foreach ($arguments as $argument) {
            $resolved[] = $this->resolveArgument($argument);
        }

        return $resolved;
    }

    private function resolveArgument($argument)
    {
        // Service reference (commence par @)
        if (is_string($argument) && str_starts_with($argument, '@')) {
            $serviceId = substr($argument, 1);
            return $this->get($serviceId);
        }

        // Paramètre scalaire
        if (is_scalar($argument)) {
            return $argument;
        }

        // Array d'arguments
        if (is_array($argument)) {
            return $this->resolveArguments($argument);
        }

        return $argument;
    }

    private function loadServicesFromYaml(): void
    {
        $yamlFile = __DIR__ . '/services.yml';
        if (!file_exists($yamlFile)) {
            return;
        }

        $yamlContent = yaml_parse_file($yamlFile);
        
        if (!$yamlContent) {
            return;
        }

        // Parser le nouveau format
        foreach ($yamlContent as $serviceId => $definition) {
            if (is_array($definition) && isset($definition['class'])) {
                // Nouveau format avec class et arguments
                $this->definitions[$serviceId] = $definition;
            } else {
                // Ancien format (rétrocompatibilité)
                $this->definitions[$serviceId] = [
                    'class' => $definition,
                    'arguments' => []
                ];
            }
        }
    }

    /**
     * Debug: Afficher tous les services enregistrés
     */
    public function getRegisteredServices(): array
    {
        return array_keys($this->definitions);
    }

    /**
     * Debug: Afficher la définition d'un service
     */
    public function getServiceDefinition(string $serviceId): ?array
    {
        return $this->definitions[$serviceId] ?? null;
    }

    /**
     * Vider le cache des instances (utile pour les tests)
     */
    public function clearInstances(): void
    {
        $this->instances = [];
    }
}
