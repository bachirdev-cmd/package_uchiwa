<?php

namespace AppDAF\CORE\ATTRIBUTES;

use Attribute;

#[Attribute(Attribute::TARGET_PARAMETER)]
class Inject
{
    public function __construct(
        public readonly ?string $service = null
    ) {}
}
