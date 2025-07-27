<?php

namespace AppDAF\CORE;

use ReflectionClass;
use ReflectionMethod;

class ReflectionFactory
{
    private static array $instances = [];

    /**
     * Créer une instance dynamiquement avec injection de dépendances
     */
    public static function create(string $className, array $parameters = []): object
    {
        $reflection = new ReflectionClass($className);
        
        if (!$reflection->isInstantiable()) {
            throw new \Exception("La classe {$className} n'est pas instanciable");
        }

        $constructor = $reflection->getConstructor();
        
        if (!$constructor) {
            return new $className();
        }

        $dependencies = self::buildDependencies($constructor, $parameters);
        
        return $reflection->newInstanceArgs($dependencies);
    }

    /**
     * Créer un singleton avec réflexion
     */
    public static function singleton(string $className): object
    {
        if (!isset(self::$instances[$className])) {
            self::$instances[$className] = self::create($className);
        }
        
        return self::$instances[$className];
    }

    /**
     * Appeler une méthode dynamiquement avec injection
     */
    public static function callMethod(object $instance, string $methodName, array $parameters = [])
    {
        $reflection = new ReflectionClass($instance);
        $method = $reflection->getMethod($methodName);
        
        $dependencies = self::buildDependencies($method, $parameters);
        
        return $method->invokeArgs($instance, $dependencies);
    }

    /**
     * Analyser les propriétés d'une classe
     */
    public static function getClassInfo(string $className): array
    {
        $reflection = new ReflectionClass($className);
        
        return [
            'name' => $reflection->getName(),
            'properties' => array_map(fn($prop) => [
                'name' => $prop->getName(),
                'type' => $prop->getType()?->getName(),
                'public' => $prop->isPublic(),
                'static' => $prop->isStatic()
            ], $reflection->getProperties()),
            'methods' => array_map(fn($method) => [
                'name' => $method->getName(),
                'parameters' => array_map(fn($param) => [
                    'name' => $param->getName(),
                    'type' => $param->getType()?->getName(),
                    'optional' => $param->isOptional()
                ], $method->getParameters())
            ], $reflection->getMethods()),
            'interfaces' => $reflection->getInterfaceNames(),
            'parent' => $reflection->getParentClass()?->getName()
        ];
    }

    private static function buildDependencies(ReflectionMethod $method, array $userParams = []): array
    {
        $dependencies = [];
        $parameters = $method->getParameters();

        foreach ($parameters as $parameter) {
            $paramName = $parameter->getName();
            $paramType = $parameter->getType();

            // Si l'utilisateur a fourni cette dépendance
            if (isset($userParams[$paramName])) {
                $dependencies[] = $userParams[$paramName];
                continue;
            }

            // Si c'est une classe, l'instancier
            if ($paramType && !$paramType->isBuiltin()) {
                $dependencies[] = self::create($paramType->getName());
                continue;
            }

            // Valeur par défaut
            if ($parameter->isDefaultValueAvailable()) {
                $dependencies[] = $parameter->getDefaultValue();
                continue;
            }

            throw new \Exception("Impossible de résoudre le paramètre: {$paramName}");
        }

        return $dependencies;
    }
}
