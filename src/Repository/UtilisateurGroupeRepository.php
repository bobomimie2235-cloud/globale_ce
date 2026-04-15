<?php

namespace App\Repository;

use App\Entity\UtilisateurGroupe;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class UtilisateurGroupeRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, UtilisateurGroupe::class);
    }

    /**
     * Retourne tous les groupes avec le nombre d'utilisateurs dans chaque groupe,
     * triés par nombre d'utilisateurs décroissant
     */
    public function findGroupesAvecStats(): array
    {
        return $this->createQueryBuilder('g')
            ->select('g.nomGroupe', 'g.referenceGroupe', 'COUNT(u.id) AS nbUtilisateurs')
            ->leftJoin('g.utilisateurs', 'u')
            ->groupBy('g.id')
            ->orderBy('nbUtilisateurs', 'DESC')
            ->getQuery()
            ->getResult();
    }

        public function findBySearch(?string $search): array
    {
        return $this->createQueryBuilder('g')
            ->where('g.nomGroupe LIKE :search OR g.referenceGroupe LIKE :search OR g.ville LIKE :search OR g.email LIKE :search')
            ->setParameter('search', '%' . $search . '%')
            ->orderBy('g.nomGroupe', 'ASC')
            ->getQuery()
            ->getResult();
    }
}
