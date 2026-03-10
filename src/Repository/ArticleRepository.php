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

    /**
     * Nombre total d'articles
     */
    public function countAll(): int
    {
        return (int) $this->createQueryBuilder('a')
            ->select('COUNT(a.id)')
            ->getQuery()
            ->getSingleScalarResult();
    }

    /**
     * Les N derniers articles avec leur auteur et catégorie
     */
    public function findDerniers(int $limit = 5): array
    {
        return $this->createQueryBuilder('a')
            ->leftJoin('a.utilisateur', 'u')
            ->leftJoin('a.articleCategorie', 'ac')
            ->addSelect('u', 'ac')
            ->orderBy('a.id', 'DESC')
            ->setMaxResults($limit)
            ->getQuery()
            ->getResult();
    }

    /**
     * Ajout à ajouter dans src/Repository/ArticleRepository.php
     */
    public function findByFiltresAdmin(?string $search, ?int $categorieId, ?int $deptId): array
    {
        $qb = $this->createQueryBuilder('a')
            ->leftJoin('a.articleCategorie', 'ac')
            ->leftJoin('a.departement', 'd')
            ->leftJoin('a.utilisateur', 'u')
            ->addSelect('ac', 'd', 'u')
            ->orderBy('a.titre', 'ASC');

        if ($search) {
            $qb->andWhere('a.titre LIKE :search OR a.description LIKE :search OR a.offreCommerciale LIKE :search')
                ->setParameter('search', '%' . $search . '%');
        }

        if ($categorieId) {
            $qb->andWhere('ac.id = :categorieId')
                ->setParameter('categorieId', $categorieId);
        }

        if ($deptId) {
            $qb->andWhere('d.id = :deptId')
                ->setParameter('deptId', $deptId);
        }

        return $qb->getQuery()->getResult();
    }
}
