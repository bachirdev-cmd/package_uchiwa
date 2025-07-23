<?php

namespace AppDAF\SERVICE;

use AppDAF\CORE\App;
use AppDAF\CORE\Singleton;
use AppDAF\ENTITY\LogEntity;
use AppDAF\ENUM\ClassName;
use AppDAF\REPOSITORY\LogRepository;

class LogService extends Singleton
{
    private LogRepository $logRepository;

    public function __construct()
    {
        $this->logRepository = App::getDependencie(ClassName::LOG_REPOSITOTY);
    }

    public function save(LogEntity $log): int
    {
        return $this->logRepository->insertLog($log);
    }
}
