<?php

namespace App\Repository;

use App\Entity\Produit;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Produit>
 */
class ProduitRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Produit::class);
    }

        public function search(string $q): array
{
    return $this->createQueryBuilder('p')
        ->leftJoin('p.produitCategorie', 'cat')
        ->where('p.intitule LIKE :q
        OR p.reference LIKE :q
        OR p.description LIKE :q
        OR cat.intitule LIKE :q')
        ->setParameter('q', '%' . $q . '%')
        ->orderBy('p.intitule', 'ASC')
        ->getQuery()
        ->getResult();
}

    /**
     * @param int[]       $categorieIds
     * @param int[]       $departementIds
     * @param string|null $search         Recherche sur intitule, reference, description
     * @return Produit[]
     */
    public function findByFiltres(array $categorieIds, array $departementIds, ?string $search = null): array
    {
        $qb = $this->createQueryBuilder('p');

        if (!empty($categorieIds)) {
            $qb->andWhere('p.produitCategorie IN (:categorieIds)')
            ->setParameter('categorieIds', $categorieIds);
        }

        if (!empty($departementIds)) {
            $qb->andWhere('p.departement IN (:departementIds)')
            ->setParameter('departementIds', $departementIds);
        }

        if ($search) {
            $qb->andWhere('p.intitule LIKE :search OR p.reference LIKE :search OR p.description LIKE :search')
            ->setParameter('search', '%' . $search . '%');
        }

        return $qb->orderBy('p.intitule', 'ASC')
                ->getQuery()
                ->getResult();
    }

        /**
     * Nombre total de produits
     */
    public function countAll(): int
    {
        return (int) $this->createQueryBuilder('p')
            ->select('COUNT(p.id)')
            ->getQuery()
            ->getSingleScalarResult();
    }
}
