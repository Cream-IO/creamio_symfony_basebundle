<?php

namespace CreamIO\BaseBundle\Repository;

use CreamIO\BaseBundle\Entity\DatabaseLog;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method DatabaseLog|null find($id, $lockMode = null, $lockVersion = null)
 * @method DatabaseLog|null findOneBy(array $criteria, array $orderBy = null)
 * @method DatabaseLog[]    findAll()
 * @method DatabaseLog[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class DatabaseLogRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, DatabaseLog::class);
    }
}
