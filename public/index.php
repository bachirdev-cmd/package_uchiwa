<?php

use AppDAF\CORE\Router;

require_once '../app/config/bootstrap-new.php';

Router::setRoute($routes);

Router::resolve();