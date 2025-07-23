<?php
namespace AppDAF\ENTITY;

use AppDAF\ABSTRACT\AbstractEntity;

class LogEntity extends AbstractEntity
{
    protected int $id;
    protected string $date;
    protected string $heure;
    protected string $localisation;
    protected string $ip_address;
    protected Statut $statut;

    public function __construct(string $date, string $heure, string $localisation, string $ip_address, Statut $statut)
        {
            $this->date = $date;
            $this->heure = $heure;
            $this->localisation = $localisation;
            $this->ip_address = $ip_address;
            $this->statut = $statut;

        }
    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'date' => $this->date,
            'heure' => $this->heure,
            'localisation' => $this->localisation,
            'ip_address' => $this->ip_address,
            // 'statut' => $this->statut->getValue(),
        ];

    }

    public static function toObject($data): static
        {
            return new static(
                $data['date'],
                $data['heure'],
                $data['localisation'],
                $data['ip_adress'],
                Statut::from($data['statut'])
            );
        }
}