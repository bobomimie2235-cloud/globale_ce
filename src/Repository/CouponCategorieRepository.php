<?php

namespace App\Repository;

use App\Entity\CouponCategorie;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<CouponCategorie>
 */
class CouponCategorieRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, CouponCategorie::class);
    }

    public function search(string $q): array
{
    return $this->createQueryBuilder('c')
        ->leftJoin('c.couponCategorie', 'cat')
        ->where('c.intitule LIKE :q
        OR c.offreCommerciale LIKE :q
        OR c.ville LIKE :q
        OR cat.intitule LIKE :q')
        ->setParameter('q', '%' . $q . '%')
        ->orderBy('c.intitule', 'ASC')
        ->getQuery()
        ->getResult();
}

    //    /**
    //     * @return CouponCategorie[] Returns an array of CouponCategorie objects
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

    //    public function findOneBySomeField($value): ?CouponCategorie
    //    {
    //        return $this->createQueryBuilder('c')
    //            ->andWhere('c.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
