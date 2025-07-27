<?php

namespace AppDAF\CORE;

use AppDAF\ENUM\ClassName;
use AppDAF\CONFIG\ServiceContainer;
use AppDAF\CONFIG\ReflectionServiceContainer;
use AppDAF\CONFIG\AdvancedServiceContainer;

class App
{
    private static ?ServiceContainer $container = null;
    private static bool $useReflection = true;

    public static function getDependencie(ClassName $className): mixed
    {
        if (self::$container === null) {
            self::$container = new AdvancedServiceContainer();
        }

        if (!self::$container->has($className->value)) {
            throw new \Exception("La dependance {$className->value} est introuvable", 1);
        }

        return self::$container->get($className->value);
    }

    public static function getContainer(): ServiceContainer
    {
        if (self::$container === null) {
            self::$container = new AdvancedServiceContainer();
        }
        
        return self::$container;
    }

    public static function enableReflection(bool $enable = true): void
    {
        self::$useReflection = $enable;
        self::$container = null; // Reset container
    }

    public static function create(string $className, array $params = []): object
    {
        return ReflectionFactory::create($className, $params);
    }
}