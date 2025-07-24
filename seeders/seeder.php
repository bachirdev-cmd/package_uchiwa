<?php

require_once __DIR__ . '/../vendor/autoload.php';

use AppDAF\CONFIG\CloudinaryConfig;
use AppDAF\CONFIG\DatabaseConfig;
use AppDAF\CONFIG\EnvironmentConfig;
use AppDAF\SEEDERS\SERVICES\CloudinaryUploadService;
use AppDAF\SEEDERS\SERVICES\CitoyenSeederService;
use Dotenv\Dotenv;

// Charger les variables d'environnement
$dotenv = Dotenv::createImmutable(__DIR__ . '/../');
$dotenv->load();

try {
    echo "🚀 Démarrage du seeder...\n\n";

    // Configuration avec injection des dépendances via les interfaces
    $envConfig = new EnvironmentConfig();
    $dbConfig = new DatabaseConfig();
    $cloudConfig = new CloudinaryConfig();

    echo "🔗 Connexion à la base de données...\n";
    
    // Services avec séparation des responsabilités
    $uploadService = new CloudinaryUploadService($cloudConfig);
    $seederService = new CitoyenSeederService($dbConfig, $uploadService);

    echo "✅ Services initialisés avec succès\n\n";

    // Exécution du seeding
    $seederService->clearTables();
    $seederService->seedCitoyens();
    $seederService->seedLogs();

    echo "🎉 Seeder terminé avec succès.\n";

} catch (\Exception $e) {
    echo "❌ Erreur lors du seeding : " . $e->getMessage() . "\n";
    echo "📍 Trace : " . $e->getTraceAsString() . "\n";
}
