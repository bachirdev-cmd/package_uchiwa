<?php

namespace AppDAF\REPOSITORY;

use AppDAF\CORE\App;
use AppDAF\ENTITY\LogEntity;
use AppDAF\ENUM\ClassName;
use PDO;

class LogRepository
{
    private PDO $pdo;

    public function __construct()
    {
        $this->pdo = App::getDependencie(ClassName::DATABASE)->getConnexion();
    }

    public function insertLog(LogEntity $logEntity): int
    {
        $query = $this->pdo->prepare('INSERT INTO log (date, heure, localisation, ip_adress, statut) VALUES (:date, :heure, :localisation, :ip_adress, :statut)');
        $data = $logEntity->toArray();
        
        $query->execute([
            'date' => $data['date'],
            'heure' => $data['heure'],
            'localisation' => $data['localisation'],
            'ip_adress' => $data['ip_adress'],
            'statut' => $data['statut'],
        ]);
        
        return (int)$this->pdo->lastInsertId();
    }

    }

