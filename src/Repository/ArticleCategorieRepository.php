<?php

namespace App\Repository;

use App\Entity\ArticleCategorie;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<ArticleCategorie>
 */
class ArticleCategorieRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ArticleCategorie::class);
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

    //    /**
    //     * @return ArticleCategorie[] Returns an array of ArticleCategorie objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('a')
    //            ->andWhere('a.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('a.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?ArticleCategorie
    //    {
    //        return $this->createQueryBuilder('a')
    //            ->andWhere('a.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
