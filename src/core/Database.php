<?php

namespace AppDAF\CORE;

use PDO;
use AppDAF\CONFIG\DatabaseConfig;

class Database extends Singleton
{
    private ?PDO $pdo = null;
    private static array $configDefault = [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES => false
    ];

    public function __construct()
    {
        try {
            $dbConfig = new DatabaseConfig();
            
            $dsn = "pgsql:host={$dbConfig->getHost()};port={$dbConfig->getPort()};dbname={$dbConfig->getDatabase()}";
            $this->pdo = new PDO(
                $dsn,
                $dbConfig->getUsername(),
                $dbConfig->getPassword(),
                self::$configDefault
            );
        } catch (\PDOException $e) {
            die('Erreur de connexion à la base de données : ' . $e->getMessage());
        }
    }

    public function getConnexion(): PDO
    {
        return $this->pdo;
    }
}
