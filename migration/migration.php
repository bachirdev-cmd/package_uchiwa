<?php

require_once __DIR__ . '/../vendor/autoload.php';

use AppDAF\MIGRATION\SERVICES\PostgreSQLMigrator;
use AppDAF\MIGRATION\SERVICES\MySQLMigrator;
use AppDAF\MIGRATION\SERVICES\MigrationService;
use AppDAF\CONFIG\EnvironmentConfig;

function prompt(string $message): string {
    echo $message;
    return trim(fgets(STDIN));
}

try {
    echo "🗄️  Configuration de la base de données\n";
    echo "=====================================\n\n";

    $driver = strtolower(prompt("Quel SGBD utiliser ? (mysql / pgsql / postgres) : "));
    
    // Normaliser le driver
    if (in_array($driver, ['postgres', 'postgresql'])) {
        $driver = 'pgsql';
    }
    $host = prompt("Hôte (default: 127.0.0.1) : ") ?: "127.0.0.1";
    $port = prompt("Port (default: 3306 ou 5432) : ") ?: ($driver === 'pgsql' ? "5432" : "3306");
    $user = prompt("Utilisateur (default: root/postgres) : ") ?: ($driver === 'pgsql' ? "db" : "root");
    $pass = prompt("Mot de passe : ");
    $dbName = prompt("Nom de la base à créer : ");

    // Initialiser les services avec injection de dépendances
    $envConfig = new EnvironmentConfig();
    
    // Factory pattern pour créer le bon migrator
    $migrator = match($driver) {
        'pgsql', 'postgres', 'postgresql' => new PostgreSQLMigrator($host, $port, $user, $pass),
        'mysql' => new MySQLMigrator($host, $port, $user, $pass),
        default => throw new \InvalidArgumentException("SGBD non supporté : $driver")
    };

    // Stocker temporairement les configs pour la génération du .env
    $_ENV['DB_HOST'] = $host;
    $_ENV['DB_PORT'] = $port;
    $_ENV['DB_USER'] = $user;
    $_ENV['DB_PASS'] = $pass;

    $migrationService = new MigrationService($migrator, $envConfig);
    $migrationService->runMigration($dbName);

    echo "\n🎉 Migration terminée avec succès !\n";

} catch (\Exception $e) {
    echo "❌ Erreur lors de la migration : " . $e->getMessage() . "\n";
    echo "📍 Trace : " . $e->getTraceAsString() . "\n";
}
