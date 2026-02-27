<?php

namespace App\Repository;

use App\Entity\CouponReduction;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<CouponReduction>
 */
class CouponReductionRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, CouponReduction::class);
    }

    public function search(string $q): array
{
    return $this->createQueryBuilder('c')
        ->leftJoin('c.couponCategorie', 'cat')
        ->where('c.intitule LIKE :q 
            OR c.offreCommerciale LIKE :q 
            OR c.ville LIKE :q 
            OR c.reference LIKE :q
            OR cat.categorie LIKE :q')
        ->setParameter('q', '%' . $q . '%')
        ->orderBy('c.intitule', 'ASC')
        ->getQuery()
        ->getResult();
}

    //    /**
    //     * @return CouponReduction[] Returns an array of CouponReduction objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('c')
    //            ->andWhere('c.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('c.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?CouponReduction
    //    {
    //        return $this->createQueryBuilder('c')
    //            ->andWhere('c.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
