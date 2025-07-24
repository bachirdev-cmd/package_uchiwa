<?php
require_once '../vendor/autoload.php';

use AppDAF\CONFIG\DatabaseConfig;
use AppDAF\CONFIG\EnvironmentConfig;

header('Content-Type: application/json');

try {
    // Charger l'environnement
    $envConfig = new EnvironmentConfig();
    $dbConfig = new DatabaseConfig();
    
    echo "🔍 Test de connexion PostgreSQL\n";
    echo "================================\n\n";
    
    echo "📋 Configuration :\n";
    echo "Host: " . $dbConfig->getHost() . "\n";
    echo "Port: " . $dbConfig->getPort() . "\n";
    echo "Database: " . $dbConfig->getDatabase() . "\n";
    echo "User: " . $dbConfig->getUsername() . "\n";
    echo "Password: " . (empty($dbConfig->getPassword()) ? '❌ VIDE' : '✅ Défini') . "\n\n";
    
    // Test de connexion
    $dsn = "pgsql:host={$dbConfig->getHost()};port={$dbConfig->getPort()};dbname={$dbConfig->getDatabase()}";
    $pdo = new PDO($dsn, $dbConfig->getUsername(), $dbConfig->getPassword());
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    echo "✅ Connexion PostgreSQL réussie !\n\n";
    
    // Test de requête
    $stmt = $pdo->query("SELECT COUNT(*) as total FROM citoyen");
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    
    echo "📊 Nombre de citoyens : " . $result['total'] . "\n";
    echo "🎉 Test complet réussi !\n";
    
} catch (Exception $e) {
    echo "❌ ERREUR : " . $e->getMessage() . "\n";
    echo "📍 Code : " . $e->getCode() . "\n";
}
?>
