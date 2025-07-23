<?php
namespace AppDAF\ENUM;

enum KeyRoute : string{
    case CONTROLLER = 'CONTROLLER';
    case ACTION = 'ACTION';
    case MIDDLEWARES = 'MIDDLEWARES';
}