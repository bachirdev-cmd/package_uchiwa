<?php

namespace AppDAF\CORE\ATTRIBUTES;

use Attribute;

#[Attribute(Attribute::TARGET_CLASS)]
class Injectable
{
    public function __construct(
        public readonly bool $singleton = false,
        public readonly string $scope = 'request'
    ) {}
}
