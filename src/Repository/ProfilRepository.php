<?php

namespace App\Repository;

use App\Entity\Profil;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Profil>
 */
class ProfilRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Profil::class);
    }

    public function existsById(int $id): bool
    {
        return (bool) $this->createQueryBuilder('p')
            ->select('COUNT(p.id)')
            ->where('p.id = :id')
            ->setParameter('id', $id)
            ->getQuery()
            ->getSingleScalarResult();
    }

    /**
     * Get profil with all its authorizations
     */
    public function findRoleWithAuthorizationDetails(int $id): Profil
    {
        return $this->createQueryBuilder('p')
            ->leftJoin('p.authorizations', 'a')
            ->addSelect('a')
            ->where('p.id = :profilId')
            ->setParameter('profilId', $id)
            ->getQuery()
            ->getOneOrNullResult();
    }
}
