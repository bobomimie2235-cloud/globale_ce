<?php

namespace App\Repository;

use App\Entity\Departement;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Departement>
 */
class DepartementRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Departement::class);
    }

    /**
     * Retourne uniquement les départements qui ont au moins un article rattaché.
     * Utile pour ne pas afficher des départements vides dans les filtres.
     */
    public function findAvecArticles(): array
    {
        return $this->createQueryBuilder('d')
            ->innerJoin('d.articles', 'a')
            ->orderBy('d.numero', 'ASC')
            ->getQuery()
            ->getResult();
    }
}