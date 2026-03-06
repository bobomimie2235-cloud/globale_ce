<?php

namespace App\Repository;

use App\Entity\Article;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Article>
 */
class ArticleRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Article::class);
    }

    /**
     * Recherche full-text utilisée par le SearchController global.
     */
    public function search(string $q): array
    {
        return $this->createQueryBuilder('a')
            ->leftJoin('a.articleCategorie', 'cat')
            ->where('a.titre LIKE :q
            OR a.description LIKE :q
            OR a.offreCommerciale LIKE :q
            OR cat.intitule LIKE :q')
            ->setParameter('q', '%' . $q . '%')
            ->orderBy('a.titre', 'ASC')
            ->getQuery()
            ->getResult();
    }

    /**
     * @param int[]       $categorieIds
     * @param int[]       $departementIds
     * @param string|null $search         Recherche sur titre, description, offreCommerciale
     * @return Article[]
     */
    public function findByFiltres(array $categorieIds, array $departementIds, ?string $search = null): array
    {
        $qb = $this->createQueryBuilder('a');

        if (!empty($categorieIds)) {
            $qb->andWhere('a.articleCategorie IN (:categorieIds)')
               ->setParameter('categorieIds', $categorieIds);
        }

        if (!empty($departementIds)) {
            $qb->andWhere('a.departement IN (:departementIds)')
               ->setParameter('departementIds', $departementIds);
        }

        if ($search) {
            $qb->andWhere('a.titre LIKE :search OR a.description LIKE :search OR a.offreCommerciale LIKE :search')
               ->setParameter('search', '%' . $search . '%');
        }

        return $qb->orderBy('a.titre', 'ASC')
                  ->getQuery()
                  ->getResult();
    }
}
