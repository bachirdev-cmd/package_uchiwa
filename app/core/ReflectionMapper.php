<?php

namespace AppDAF\CORE;

use ReflectionClass;
use ReflectionProperty;

class ReflectionMapper
{
    /**
     * Mapper un tableau vers un objet
     */
    public static function mapToObject(array $data, string $className): object
    {
        $reflection = new ReflectionClass($className);
        $instance = $reflection->newInstanceWithoutConstructor();

        foreach ($data as $key => $value) {
            self::setProperty($instance, $key, $value, $reflection);
        }

        return $instance;
    }

    /**
     * Mapper un objet vers un tableau
     */
    public static function mapToArray(object $object): array
    {
        $reflection = new ReflectionClass($object);
        $data = [];

        foreach ($reflection->getProperties() as $property) {
            $property->setAccessible(true);
            $data[$property->getName()] = $property->getValue($object);
        }

        return $data;
    }

    /**
     * Mapper entre deux objets
     */
    public static function mapObjects(object $source, object $target): object
    {
        $sourceReflection = new ReflectionClass($source);
        $targetReflection = new ReflectionClass($target);

        foreach ($sourceReflection->getProperties() as $sourceProperty) {
            $sourceProperty->setAccessible(true);
            $propertyName = $sourceProperty->getName();

            if ($targetReflection->hasProperty($propertyName)) {
                $targetProperty = $targetReflection->getProperty($propertyName);
                $targetProperty->setAccessible(true);
                $targetProperty->setValue($target, $sourceProperty->getValue($source));
            }
        }

        return $target;
    }

    /**
     * Validation automatique basée sur les types
     */
    public static function validate(object $object): array
    {
        $reflection = new ReflectionClass($object);
        $errors = [];

        foreach ($reflection->getProperties() as $property) {
            $property->setAccessible(true);
            $value = $property->getValue($object);
            $type = $property->getType();

            if ($type && !$type->isBuiltin()) {
                continue; // Skip object types
            }

            $typeName = $type?->getName();
            $isValid = match($typeName) {
                'int' => is_int($value),
                'float' => is_float($value),
                'string' => is_string($value),
                'bool' => is_bool($value),
                'array' => is_array($value),
                default => true
            };

            if (!$isValid) {
                $errors[] = "Property {$property->getName()} should be of type {$typeName}";
            }
        }

        return $errors;
    }

    private static function setProperty(object $instance, string $propertyName, $value, ReflectionClass $reflection): void
    {
        if ($reflection->hasProperty($propertyName)) {
            $property = $reflection->getProperty($propertyName);
            $property->setAccessible(true);
            
            // Conversion de type automatique
            $type = $property->getType();
            if ($type && $type->isBuiltin()) {
                $value = self::castValue($value, $type->getName());
            }
            
            $property->setValue($instance, $value);
        }
    }

    private static function castValue($value, string $type)
    {
        return match($type) {
            'int' => (int) $value,
            'float' => (float) $value,
            'string' => (string) $value,
            'bool' => (bool) $value,
            'array' => is_array($value) ? $value : [$value],
            default => $value
        };
    }
}
