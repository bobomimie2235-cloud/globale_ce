<?php

namespace App\Repository;

use App\Entity\Utilisateur;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class UtilisateurRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Utilisateur::class);
    }

    /**
     * Nombre d'utilisateurs inscrits ce mois-ci
     */
    public function countThisMonth(): int
    {
        $debut = new \DateTimeImmutable('first day of this month midnight');
        $fin   = new \DateTimeImmutable('last day of this month 23:59:59');

        return (int) $this->createQueryBuilder('u')
            ->select('COUNT(u.id)')
            ->where('u.dateInscription BETWEEN :debut AND :fin')
            ->setParameter('debut', $debut)
            ->setParameter('fin', $fin)
            ->getQuery()
            ->getSingleScalarResult();
    }

    /**
     * Les N derniers utilisateurs inscrits
     */
    public function findDerniers(int $limit = 5): array
    {
        return $this->createQueryBuilder('u')
            ->orderBy('u.dateInscription', 'DESC')
            ->setMaxResults($limit)
            ->getQuery()
            ->getResult();
    }
}
