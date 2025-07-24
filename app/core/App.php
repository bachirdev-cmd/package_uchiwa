<?php

namespace AppDAF\CORE;

use AppDAF\ENUM\ClassName;
use AppDAF\CONFIG\ServiceContainer;

class App
{
    private static ?ServiceContainer $container = null;

    public static function getDependencie(ClassName $className): mixed
    {
        if (self::$container === null) {
            self::$container = new ServiceContainer();
        }

        if (!self::$container->has($className->value)) {
            throw new \Exception("La dependance {$className->value} est introuvable", 1);
        }

        return self::$container->get($className->value);
    }

    public static function getContainer(): ServiceContainer
    {
        if (self::$container === null) {
            self::$container = new ServiceContainer();
        }
        
        return self::$container;
    }
}