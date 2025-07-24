<?php

// Bootstrap pour production Render
$rootPath = __DIR__ . '/../../';

require_once $rootPath . 'vendor/autoload.php';

// Charger helpers
require_once $rootPath . 'app/config/helpers.php';

// Charger routes
require_once $rootPath . 'routes/route.web.php';

// Note: Les variables d'environnement sont injectées par Render
// Pas besoin de charger env.php en production
