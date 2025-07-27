<?php
namespace AppDAF\CORE;

use AppDAF\ABSTRACT\AbstractController;
use AppDAF\ENTITY\ResponseEntity;
use AppDAF\ENTITY\Statut;

class ErrorController extends AbstractController
{

    public function _404() : void {
        $response = new ResponseEntity('Ressource not found', Statut::ERROR,404);
        $this->renderJson($response);
    } 
}