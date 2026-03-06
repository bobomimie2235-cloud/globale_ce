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

    /**
     * @param int[]       $categorieIds
     * @param int[]       $departementIds
     * @param string|null $search         Recherche sur intitule, offreCommerciale, ville
     * @return CouponReduction[]
     */
    public function findByFiltres(array $categorieIds, array $departementIds, ?string $search = null): array
    {
        $qb = $this->createQueryBuilder('c');

        if (!empty($categorieIds)) {
            $qb->andWhere('c.couponCategorie IN (:categorieIds)')
               ->setParameter('categorieIds', $categorieIds);
        }

        if (!empty($departementIds)) {
            $qb->andWhere('c.departement IN (:departementIds)')
               ->setParameter('departementIds', $departementIds);
        }

        if ($search) {
            $qb->andWhere('c.intitule LIKE :search OR c.offreCommerciale LIKE :search OR c.ville LIKE :search')
               ->setParameter('search', '%' . $search . '%');
        }

        return $qb->orderBy('c.intitule', 'ASC')
                  ->getQuery()
                  ->getResult();
    }
}
