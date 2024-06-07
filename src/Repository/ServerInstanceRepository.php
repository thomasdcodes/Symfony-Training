<?php

declare(strict_types=1);

namespace App\Repository;

use App\Entity\ServerInstance;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method ServerInstance|null find($id, $lockMode = null, $lockVersion = null)
 * @method ServerInstance|null findOneBy(array $criteria, array $orderBy = null)
 * @method ServerInstance[]    findAll()
 * @method ServerInstance[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 * @extends ServiceEntityRepository<ServerInstance>
 */
class ServerInstanceRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ServerInstance::class);
    }

    /**
     * @return ServerInstance[]
     */
    public function findActive(): array
    {
        return $this->createQueryBuilder('si')
            ->andWhere('si.status = :status')
            ->setParameter('status', 'active')
            ->orderBy('si.name', 'ASC')
            ->getQuery()
            ->getResult()
        ;
    }
}