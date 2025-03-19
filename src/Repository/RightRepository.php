<?php

namespace App\Repository;

use App\Entity\Right;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Right>
 */
class RightRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Right::class);
    }

    /**
     * Find all Rights associated with a given Profil ID.
     *
     * @param int $profilId
     * @return Right[]
     */
    public function findRightsByProfilId(int $profilId): array
    {
        return $this->createQueryBuilder('r')
            ->innerJoin('r.profils', 'p')
            ->where('p.id = :profilId')
            ->setParameter('profilId', $profilId)
            ->getQuery()
            ->getResult();
    }

}
