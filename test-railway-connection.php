<?php

echo "🔍 Test de connexion Railway PostgreSQL\n";
echo "=====================================\n\n";

$host = 'turntable.proxy.rlwy.net';
$port = '34165';
$dbname = 'railway';
$user = 'postgres';
$password = 'NpkRAcBICMTntChIcazLdXOboNQwfQcW';

try {
    $dsn = "pgsql:host=$host;port=$port;dbname=$dbname";
    $pdo = new PDO($dsn, $user, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    echo "✅ Connexion Railway PostgreSQL réussie !\n";
    
    // Test de requête
    $stmt = $pdo->query("SELECT COUNT(*) as total FROM citoyen");
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    
    echo "📊 Nombre de citoyens : " . $result['total'] . "\n";
    
    // Test de récupération du citoyen
    $stmt = $pdo->prepare("SELECT * FROM citoyen WHERE cni = ?");
    $stmt->execute(['CNI9876543210']);
    $citoyen = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if ($citoyen) {
        echo "👤 Citoyen trouvé : " . $citoyen['nom'] . " " . $citoyen['prenom'] . "\n";
        echo "🎉 Tout fonctionne parfaitement !\n";
    } else {
        echo "❌ Citoyen CNI9876543210 non trouvé\n";
    }
    
} catch (Exception $e) {
    echo "❌ ERREUR : " . $e->getMessage() . "\n";
}

?>
