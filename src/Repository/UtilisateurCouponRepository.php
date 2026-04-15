<?php

namespace App\Repository;

use App\Entity\Utilisateur;
use App\Entity\UtilisateurCoupon;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class UtilisateurCouponRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, UtilisateurCoupon::class);
    }

    public function findByUtilisateur(Utilisateur $utilisateur): array
{
    return $this->createQueryBuilder('uc')
        ->where('uc.utilisateur = :utilisateur')
        ->setParameter('utilisateur', $utilisateur)
        ->orderBy('uc.dateUtilisation', 'DESC')
        ->getQuery()
        ->getResult();
}

}
