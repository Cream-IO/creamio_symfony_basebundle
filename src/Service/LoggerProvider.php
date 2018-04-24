<?php

namespace CreamIO\BaseBundle\Service;

use Symfony\Bridge\Monolog\Logger;

/**
 * Provides acces to the DB logger service. Avoids getting it from container through ->get('monolog.logger.db').
 */
class LoggerProvider
{
    /**
     * @var Logger Injecter BG logger
     */
    private $loggerService;

    /**
     * LoggerProvider constructor.
     *
     * @param Logger $logger Injected DB logger
     */
    public function __construct(Logger $logger)
    {
        $this->loggerService = $logger;
    }

    /**
     * Provides DB logger.
     *
     * @return Logger
     */
    public function logger(): Logger
    {
        return $this->loggerService;
    }
}