<?php

namespace App\Repository;

use App\Entity\ProduitCategorie;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class ProduitCategorieRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ProduitCategorie::class);
    }

    /**
     * Nombre total de catégories produits
     */
    public function countAll(): int
    {
        return (int) $this->createQueryBuilder('pc')
            ->select('COUNT(pc.id)')
            ->getQuery()
            ->getSingleScalarResult();
    }
}
