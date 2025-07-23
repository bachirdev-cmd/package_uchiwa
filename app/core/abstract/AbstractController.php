<?php

namespace AppDAF\ABSTRACT;

use AppDAF\CORE\Singleton;
use AppDAF\ENTITY\ResponseEntity;

abstract class AbstractController extends Singleton
{

    protected function renderJson(ResponseEntity $response): void
    {
        http_response_code(201);

        header('Content-Type: application/json');

        echo $response->toJson();
    }
}
