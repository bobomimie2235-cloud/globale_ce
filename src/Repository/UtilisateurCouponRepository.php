<?php

namespace App\Repository;

use App\Entity\UtilisateurCoupon;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<UtilisateurCoupon>
 */
class UtilisateurCouponRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, UtilisateurCoupon::class);
    }

        /**
     * À ajouter dans src/Repository/UtilisateurGroupeRepository.php
     */
    public function findBySearch(?string $search): array
    {
        return $this->createQueryBuilder('g')
            ->where('g.nomGroupe LIKE :search OR g.referenceGroupe LIKE :search OR g.ville LIKE :search OR g.email LIKE :search')
            ->setParameter('search', '%' . $search . '%')
            ->orderBy('g.nomGroupe', 'ASC')
            ->getQuery()
            ->getResult();
    }
    
    //    /**
    //     * @return UtilisateurCoupon[] Returns an array of UtilisateurCoupon objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('u')
    //            ->andWhere('u.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('u.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?UtilisateurCoupon
    //    {
    //        return $this->createQueryBuilder('u')
    //            ->andWhere('u.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
