<?php

use AppDAF\CONTROLLER\CitoyenController;
use AppDAF\CORE\ErrorController;
use AppDAF\ENUM\KeyRoute;

$routes = [
    '/404' => [
        KeyRoute::CONTROLLER->value => ErrorController::class,
        KeyRoute::ACTION->value => '_404',
        KeyRoute::MIDDLEWARES->value => []
    ],
    '/citoyen/{cni}' => [
        KeyRoute::CONTROLLER->value => CitoyenController::class,
        KeyRoute::ACTION->value => 'recherche',
        KeyRoute::MIDDLEWARES->value => []
    ]
];
