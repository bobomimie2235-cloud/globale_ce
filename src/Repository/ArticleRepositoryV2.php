<?php

namespace App\Repository;

use App\Entity\Article;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class ArticleRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Article::class);
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
}
