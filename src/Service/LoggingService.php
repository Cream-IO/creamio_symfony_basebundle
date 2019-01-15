<?php

namespace CreamIO\BaseBundle\Service;

use CreamIO\BaseBundle\Entity\DatabaseLog;
use Doctrine\ORM\EntityManagerInterface;
use Monolog\Handler\AbstractProcessingHandler;

class LoggingService extends AbstractProcessingHandler
{
    /**
     * @var EntityManagerInterface
     */
    protected $em;

    /**
     * MonologDBHandler constructor.
     *
     * @param EntityManagerInterface $em
     */
    public function __construct(EntityManagerInterface $em)
    {
        parent::__construct();
        $this->em = $em;
    }

    /**
     * Called when writing to our database.
     *
     * @param array $record
     */
    protected function write(array $record): void
    {
        $logEntry = new DatabaseLog();
        $logEntry->setMessage($record['message']);
        $logEntry->setStatusCode($record['level']);
        $logEntry->setLevelName($record['level_name']);
        $logEntry->setExtra(json_encode($record['extra']));
        $logEntry->setContext(json_encode($record['context']));
        $this->em->persist($logEntry);
        $this->em->flush();
    }
}
