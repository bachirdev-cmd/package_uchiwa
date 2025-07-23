<?php

namespace AppDAF\CORE;

use AppDAF\ENUM\ClassName;

class App
{

    private static array $dependencies;

    public static function  getDependencie(ClassName $className): mixed
    {
        /** @var array $config */
        self::$dependencies = yaml_parse_file('../app/config/services.yml');
        if (array_key_exists($className->value, self::$dependencies)) {
            if (!method_exists(self::$dependencies[$className->value], METHODE_INSTANCE_NAME)) {
                throw new \Exception("Error Processing Request", 1);
            }

        } else {
            return throw new \Exception("La dependance $className->value est null", 1);
        }

        return self::$dependencies[$className->value]::getInstance();
    }
}
