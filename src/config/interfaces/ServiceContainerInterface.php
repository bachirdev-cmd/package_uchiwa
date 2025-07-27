<?php

namespace AppDAF\CONFIG\INTERFACES;

interface ServiceContainerInterface
{
    public function get(string $serviceId): object;
    public function has(string $serviceId): bool;
    public function register(string $serviceId, string $className): void;
}
