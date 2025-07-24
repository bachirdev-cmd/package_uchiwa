<?php

// Déterminer le chemin racine selon l'environnement
$rootPath = __DIR__ . '/../../';

require_once $rootPath . 'vendor/autoload.php';

// Charger les variables d'environnement uniquement si pas dans Docker/Production
if (!isset($_ENV['DOCKER_ENV']) && !isset($_ENV['RENDER'])) {
    require_once $rootPath . 'app/config/env.php';
}

require_once $rootPath . 'app/config/helpers.php';
require_once $rootPath . 'routes/route.web.php';