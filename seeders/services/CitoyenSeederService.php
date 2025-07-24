<?php

namespace AppDAF\SEEDERS\SERVICES;

use AppDAF\CONFIG\INTERFACES\DatabaseConfigInterface;
use PDO;

class CitoyenSeederService
{
    private PDO $pdo;
    private CloudinaryUploadService $uploadService;

    public function __construct(DatabaseConfigInterface $dbConfig, CloudinaryUploadService $uploadService)
    {
        $dsn = "pgsql:host={$dbConfig->getHost()};port={$dbConfig->getPort()};dbname={$dbConfig->getDatabase()}";
        $this->pdo = new PDO($dsn, $dbConfig->getUsername(), $dbConfig->getPassword());
        $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        
        $this->uploadService = $uploadService;
    }

    public function clearTables(): void
    {
        echo "♻️  Vidage des tables...\n";
        $this->pdo->exec("DELETE FROM log;");
        $this->pdo->exec("DELETE FROM citoyen;");
        echo "✅ Tables `citoyen` et `log` vidées avec succès.\n\n";
    }

    public function seedCitoyens(): void
    {
        $citoyens = $this->getCitoyensData();

        foreach ($citoyens as $citoyen) {
            try {
                echo "👤 Traitement de {$citoyen['nom']} {$citoyen['prenom']}...\n";
                
                $rectoPath = __DIR__ . '/../images/' . $citoyen['recto'];
                $versoPath = __DIR__ . '/../images/' . $citoyen['verso'];

                echo "📤 Upload des images CNI...\n";
                $urls = $this->uploadService->uploadCniImages($rectoPath, $versoPath);

                $this->insertCitoyen($citoyen, $urls['recto'], $urls['verso']);
                echo "✅ {$citoyen['nom']} inséré avec succès.\n\n";

            } catch (\Exception $e) {
                echo "❌ Erreur lors de l'insertion de {$citoyen['nom']} : " . $e->getMessage() . "\n";
            }
        }
    }

    public function seedLogs(): void
    {
        echo "📝 Insertion des logs...\n";
        $this->pdo->exec("
            INSERT INTO log (date, heure, localisation, ip_address, statut) VALUES
            ('2025-07-21', '14:30:00', 'Dakar - Plateau', '192.168.1.10', 'SUCCES'),
            ('2025-07-21', '15:45:12', 'Thiès - Grand Standing', '192.168.1.11', 'ERROR'),
            ('2025-07-20', '09:15:05', 'Saint-Louis - Centre-ville', '10.0.0.1', 'SUCCES');
        ");
        echo "✅ Logs insérés avec succès.\n";
    }

    private function getCitoyensData(): array
    {
        return [
            [
                'nom' => 'Gueye',
                'prenom' => 'Ramatoulaye',
                'date_naissance' => '1995-01-02',
                'lieu_naissance' => 'Dakar',
                'cni' => 'CNI1090',
                'recto' => 'cni_recto_url.png',
                'verso' => 'cni_verso_url.png'
            ],
            [
                'nom' => 'Ndour',
                'prenom' => 'Moussa',
                'date_naissance' => '1998-05-11',
                'lieu_naissance' => 'Thiès',
                'cni' => 'CNI1002',
                'recto' => 'cni_recto_url.png',
                'verso' => 'cni_verso_url.png'
            ],
            [
                'nom' => 'Fall',
                'prenom' => 'Cheikh',
                'date_naissance' => '1990-01-15',
                'lieu_naissance' => 'Saint-Louis',
                'cni' => 'CNI1003',
                'recto' => 'cni_recto_url.png',
                'verso' => 'cni_verso_url.png'
            ],
        ];
    }

    private function insertCitoyen(array $citoyen, string $rectoUrl, string $versoUrl): void
    {
        $stmt = $this->pdo->prepare("
            INSERT INTO citoyen (nom, prenom, date_naissance, lieu_naissance, cni, cni_recto_url, cni_verso_url)
            VALUES (:nom, :prenom, :date_naissance, :lieu_naissance, :cni, :cni_recto_url, :cni_verso_url)
        ");

        $stmt->execute([
            'nom' => $citoyen['nom'],
            'prenom' => $citoyen['prenom'],
            'date_naissance' => $citoyen['date_naissance'],
            'lieu_naissance' => $citoyen['lieu_naissance'],
            'cni' => $citoyen['cni'],
            'cni_recto_url' => $rectoUrl,
            'cni_verso_url' => $versoUrl
        ]);
    }
}
