<?php

namespace AppDAF\MIGRATION\SERVICES;

use AppDAF\MIGRATION\INTERFACES\DatabaseMigratorInterface;
use PDO;

class MySQLMigrator implements DatabaseMigratorInterface
{
    private PDO $pdo;
    private string $host;
    private string $port;
    private string $user;
    private string $password;
    private string $currentDb;

    public function __construct(string $host, string $port, string $user, string $password)
    {
        $this->host = $host;
        $this->port = $port;
        $this->user = $user;
        $this->password = $password;
        
        $dsn = "mysql:host={$host};port={$port}";
        $this->pdo = new PDO($dsn, $user, $password);
        $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    }

    public function getDriver(): string
    {
        return 'mysql';
    }

    public function databaseExists(string $dbName): bool
    {
        $stmt = $this->pdo->query("SHOW DATABASES LIKE '$dbName'");
        return $stmt->fetch() !== false;
    }

    public function createDatabase(string $dbName): bool
    {
        $this->currentDb = $dbName; // Toujours initialiser le nom de la base
        $this->pdo->exec("CREATE DATABASE IF NOT EXISTS $dbName CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci");
        echo "✅ Base MySQL $dbName créée ou existante.\n";
        return true;
    }

    public function createTables(): void
    {
        // Se connecter à la base de données créée
        $dsn = "mysql:host={$this->host};port={$this->port};dbname=" . $this->currentDb;
        $pdo = new PDO($dsn, $this->user, $this->password);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        
        $tables = [
            "CREATE TABLE IF NOT EXISTS citoyen (
                id INT AUTO_INCREMENT PRIMARY KEY,
                nom VARCHAR(100) NOT NULL,
                prenom VARCHAR(100) NOT NULL,
                date_naissance DATE NOT NULL,
                lieu_naissance VARCHAR(150) NOT NULL,
                cni VARCHAR(20) UNIQUE NOT NULL,
                cni_recto_url TEXT NOT NULL,
                cni_verso_url TEXT NOT NULL,
                created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
            ) ENGINE=InnoDB;",

            "CREATE INDEX idx_citoyen_cni ON citoyen(cni);",

            "CREATE TABLE IF NOT EXISTS log (
                id INT AUTO_INCREMENT PRIMARY KEY,
                date DATE NOT NULL,
                heure TIME NOT NULL,
                localisation VARCHAR(255) NOT NULL,
                ip_address VARCHAR(45) NOT NULL,
                statut ENUM('SUCCES', 'ERROR') NOT NULL,
                created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
            ) ENGINE=InnoDB;"
        ];

        foreach ($tables as $sql) {
            $pdo->exec($sql);
        }

        echo "✅ Tables MySQL créées avec succès.\n";
    }

    public function connectToDatabase(string $dbName): PDO
    {
        $dsn = "mysql:host={$this->host};port={$this->port};dbname={$dbName}";
        $pdo = new PDO($dsn, $this->user, $this->password);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        return $pdo;
    }
}
