<?php

namespace AppDAF\SERVICE;

use AppDAF\CORE\App;
use AppDAF\CORE\Singleton;
use AppDAF\ENTITY\CitoyenEntity;
use AppDAF\ENUM\ClassName;
use AppDAF\REPOSITORY\CitoyenRepository;

class CitoyenService extends Singleton
{
    private CitoyenRepository $citoyenRepository;

    public function __construct()
    {
        $this->citoyenRepository = App::getDependencie(ClassName::CITOYEN_REPOSITORY);
    }

    public function get_by_cni(string $cni): ?CitoyenEntity
    {
        return $this->citoyenRepository->selectByCni($cni);
    }


}
