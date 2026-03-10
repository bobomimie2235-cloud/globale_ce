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

    /**
     * Nombre total de coupons
     */
    public function countAll(): int
    {
        return (int) $this->createQueryBuilder('c')
            ->select('COUNT(c.id)')
            ->getQuery()
            ->getSingleScalarResult();
    }

    /**
     * Nombre de coupons actifs
     */
    public function countActifs(): int
    {
        return (int) $this->createQueryBuilder('c')
            ->select('COUNT(c.id)')
            ->where('c.actif = :actif')
            ->setParameter('actif', true)
            ->getQuery()
            ->getSingleScalarResult();
    }

    /**
     * Nombre de coupons inactifs
     */
    public function countInactifs(): int
    {
        return (int) $this->createQueryBuilder('c')
            ->select('COUNT(c.id)')
            ->where('c.actif = :actif')
            ->setParameter('actif', false)
            ->getQuery()
            ->getSingleScalarResult();
    }

    /**
     * À ajouter dans src/Repository/CouponReductionRepository.php
     */
    public function findByFiltresAdmin(?string $search, ?int $categorieId, ?int $deptId, ?bool $actif): array
    {
        $qb = $this->createQueryBuilder('c')
            ->leftJoin('c.couponCategorie', 'cc')
            ->leftJoin('c.departement', 'd')
            ->addSelect('cc', 'd')
            ->orderBy('c.intitule', 'ASC');

        if ($search) {
            $qb->andWhere('c.intitule LIKE :search OR c.reference LIKE :search OR c.ville LIKE :search OR c.offreCommerciale LIKE :search')
                ->setParameter('search', '%' . $search . '%');
        }

        if ($categorieId) {
            $qb->andWhere('cc.id = :categorieId')
                ->setParameter('categorieId', $categorieId);
        }

        if ($deptId) {
            $qb->andWhere('d.id = :deptId')
                ->setParameter('deptId', $deptId);
        }

        if ($actif !== null) {
            $qb->andWhere('c.actif = :actif')
                ->setParameter('actif', $actif);
        }

        return $qb->getQuery()->getResult();
    }
}
