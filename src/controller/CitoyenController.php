<?php

namespace AppDAF\CONTROLLER;

use AppDAF\ABSTRACT\AbstractController;
use AppDAF\CORE\App;
use AppDAF\ENTITY\ResponseEntity;
use AppDAF\ENTITY\Statut;
use AppDAF\ENUM\ClassName;
use AppDAF\SERVICE\CitoyenService;

class CitoyenController extends AbstractController
{
    private CitoyenService $citoyenService;

    public function __construct()
    {
        $this->citoyenService = App::getDependencie(ClassName::CITOYEN_SERVICE);
    }

    public function recherche($cni): void
    {

        $response = new ResponseEntity("Citoyen avec le cni $cni not found ");
        

        if ($cni) {
            $citoyen = $this->citoyenService->get_by_cni($cni);

            if ($citoyen) {
                $response->message = "Citoyen avec le cni $cni found";
                $response->data = $citoyen;
                $response->code = 200;
                $response->status = Statut::SUCCESS;
            }
        }

        $this->renderJson($response);
    }
}
