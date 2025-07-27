<?php

namespace AppDAF\MIGRATION\SERVICES;

use AppDAF\MIGRATION\INTERFACES\DatabaseMigratorInterface;
use AppDAF\CONFIG\INTERFACES\EnvironmentConfigInterface;

class MigrationService
{
    private DatabaseMigratorInterface $migrator;
    private EnvironmentConfigInterface $envConfig;

    public function __construct(DatabaseMigratorInterface $migrator, EnvironmentConfigInterface $envConfig)
    {
        $this->migrator = $migrator;
        $this->envConfig = $envConfig;
    }

    public function runMigration(string $dbName): void
    {
        echo "🚀 Démarrage de la migration avec {$this->migrator->getDriver()}...\n";
        
        // Créer la base de données
        $this->migrator->createDatabase($dbName);
        
        // Créer les tables (la connexion se fait dans le migrator)
        $this->migrator->createTables();
        
        // Générer le fichier .env
        $this->generateEnvFile($dbName);
        
        echo "✅ Migration terminée avec succès.\n";
    }

    private function generateEnvFile(string $dbName): void
    {
        $driver = $this->migrator->getDriver();
        $config = [
            'driver' => $driver,
            'host' => $this->getConfigValue('host'),
            'port' => $this->getConfigValue('port'),
            'user' => $this->getConfigValue('user'),
            'pass' => $this->getConfigValue('pass'),
            'dbname' => $dbName
        ];

        $this->writeEnv($config);
    }

    private function getConfigValue(string $key): string
    {
        // Utiliser les valeurs temporaires stockées dans $_ENV durant la migration
        switch($key) {
            case 'host': return $_ENV['DB_HOST'] ?? '127.0.0.1';
            case 'port': return $_ENV['DB_PORT'] ?? '5432';
            case 'user': return $_ENV['DB_USER'] ?? 'postgres';
            case 'pass': return $_ENV['DB_PASS'] ?? '';
            default: return '';
        }
    }

    private function writeEnv(array $config): void
    {
        $envPath = __DIR__ . '/../../.env';
        $env = <<<ENV
APP_URL=http://localhost
PGADMIN_DEFAULT_EMAIL=pabassdiame76@gmail.com
PGADMIN_DEFAULT_PASSWORD=passer123

DB_HOST={$config['host']}
DB_PORT={$config['port']}
DB_DRIVE={$config['driver']}
DB_USER={$config['user']}
DB_PASSWORD={$config['pass']}
DB_NAME={$config['dbname']}
POSTGRES_DB={$config['dbname']}
POSTGRES_USER={$config['user']}
POSTGRES_PASSWORD={$config['pass']}
DSN={$config['driver']}:host={$config['host']};port={$config['port']};dbname={$config['dbname']}
METHODE_INSTANCE_NAME=singleton
CLOUDINARY_API_KEY=your_cloudinary_api_key_here
ENV;

        file_put_contents($envPath, $env);
        echo "✅ Fichier .env généré/mis à jour avec succès.\n";
    }
}
