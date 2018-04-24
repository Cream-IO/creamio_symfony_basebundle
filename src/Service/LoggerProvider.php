<?php

namespace CreamIO\BaseBundle\Service;

class LoggerProvider
{
    private $loggerService;

    public function __construct($logger)
    {
        $this->loggerService = $logger;
    }

    public function logger()
    {
        return $this->loggerService;
    }
}