<?php
namespace AppDAF\REPOSITORY;

use AppDAF\CORE\App;
use AppDAF\ENTITY\CitoyenEntity;
use AppDAF\ENUM\ClassName;
use PDO;

class CitoyenRepository extends CitoyenEntity
{
    private PDO $pdo;

    public function __construct()
    {
        $this->pdo = App::getDependencie(ClassName::DATABASE)->getConnexion();
    }

    public function selectByCni(string $cni): ?CitoyenEntity
    {
        $query = $this->pdo->prepare('SELECT * FROM citoyen WHERE cni = :cni');
        $query->execute(['cni' => $cni]);
        $data = $query->fetch(PDO::FETCH_ASSOC);

        if (!$data) {
            return null;
        }

        $citoyen = CitoyenEntity::toObject($data);
        return $citoyen;
    }
}
