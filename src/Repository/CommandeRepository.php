<?php

namespace App\Repository;

use App\Entity\Commande;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class CommandeRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Commande::class);
    }

    /**
     * Nombre de commandes créées ce mois-ci
     */
    public function countThisMonth(): int
    {
        $debut = new \DateTime('first day of this month midnight');
        $fin   = new \DateTime('last day of this month 23:59:59');

        return (int) $this->createQueryBuilder('c')
            ->select('COUNT(c.id)')
            ->where('c.dateCommande BETWEEN :debut AND :fin')
            ->setParameter('debut', $debut)
            ->setParameter('fin', $fin)
            ->getQuery()
            ->getSingleScalarResult();
    }

    /**
     * Chiffre d'affaires du mois en cours (commandes payées uniquement)
     */
    public function caThisMonth(): string
    {
        $debut = new \DateTime('first day of this month midnight');
        $fin   = new \DateTime('last day of this month 23:59:59');

        $result = $this->createQueryBuilder('c')
            ->select('SUM(c.totalTTC)')
            ->where('c.dateCommande BETWEEN :debut AND :fin')
            ->andWhere('c.statut = :statut')
            ->setParameter('debut', $debut)
            ->setParameter('fin', $fin)
            ->setParameter('statut', 'payee')
            ->getQuery()
            ->getSingleScalarResult();

        return number_format((float) ($result ?? 0), 2, '.', '');
    }

    /**
     * Chiffre d'affaires total (commandes payées uniquement)
     */
    public function caTotal(): string
    {
        $result = $this->createQueryBuilder('c')
            ->select('SUM(c.totalTTC)')
            ->where('c.statut = :statut')
            ->setParameter('statut', 'payee')
            ->getQuery()
            ->getSingleScalarResult();

        return number_format((float) ($result ?? 0), 2, '.', '');
    }

    /**
     * Les N dernières commandes avec l'utilisateur
     */
    public function findDernieres(int $limit = 5): array
    {
        return $this->createQueryBuilder('c')
            ->leftJoin('c.utilisateur', 'u')
            ->addSelect('u')
            ->orderBy('c.dateCommande', 'DESC')
            ->setMaxResults($limit)
            ->getQuery()
            ->getResult();
    }

    /**
     * Recherche + filtre par statut pour l'admin
     */
    public function findByFiltresAdmin(?string $search, ?string $statut): array
    {
        $qb = $this->createQueryBuilder('c')
            ->leftJoin('c.utilisateur', 'u')
            ->addSelect('u')
            ->orderBy('c.dateCommande', 'DESC');

        if ($statut) {
            $qb->andWhere('c.statut = :statut')
            ->setParameter('statut', $statut);
        }

        if ($search) {
            $qb->andWhere(
                $qb->expr()->orX(
                    $qb->expr()->like('u.nom', ':search'),
                    $qb->expr()->like('u.prenom', ':search'),
                    $qb->expr()->like('u.email', ':search'),
                    $qb->expr()->like('CAST(c.id AS string)', ':search')
                )
            )->setParameter('search', '%' . $search . '%');
        }

        return $qb->getQuery()->getResult();
    }
}
