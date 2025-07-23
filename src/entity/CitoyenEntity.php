<?php
namespace AppDAF\ENTITY;

use AppDAF\CORE\Singleton;

class CitoyenEntity extends Singleton
{
    protected int $id;
    protected string $nom;
    protected string $prenom;
    protected string $cni;
    protected string $date_naissance;
    protected string $lieu_naissance;
    protected string $cni_verso_url;
    protected string $cni_recto_url;

    public function __construct(
        string $nom,
        string $prenom,
        string $cni,
        string $date_naissance,
        string $lieu_naissance,
        string $cni_verso_url,
        string $cni_recto_url
    ) {
        $this->nom = $nom;
        $this->prenom = $prenom;
        $this->cni = $cni;
        $this->date_naissance = $date_naissance;
        $this->lieu_naissance = $lieu_naissance;
        $this->cni_verso_url = $cni_verso_url;
        $this->cni_recto_url = $cni_recto_url;
    }

    public static function toObject($data): static
    {
        return new static(
            $data['nom'],
            $data['prenom'],
            $data['cni'],
            $data['date_naissance'],
            $data['lieu_naissance'],
            $data['cni_verso_url'],
            $data['cni_recto_url']
        );
    }

    public function toArray(): array
    {
        return [
            'nom'       => $this->nom,
            'prenom'    => $this->prenom,
            'cni'       => $this->cni,
            'date_naissance' => $this->date_naissance,
            'lieu_naissance'   => $this->lieu_naissance,
            'cni_verso_url'  => $this->cni_verso_url,
            'cni_recto_url'  => $this->cni_recto_url,
        ];
    }
}
