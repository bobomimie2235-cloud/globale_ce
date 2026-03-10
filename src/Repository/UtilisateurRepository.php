<?php

namespace App\Repository;

use App\Entity\Utilisateur;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Security\Core\Exception\UnsupportedUserException;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\PasswordUpgraderInterface;

/**
 * @extends ServiceEntityRepository<Utilisateur>
 */
class UtilisateurRepository extends ServiceEntityRepository implements PasswordUpgraderInterface
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Utilisateur::class);
    }

    /**
     * Used to upgrade (rehash) the user's password automatically over time.
     */
    public function upgradePassword(PasswordAuthenticatedUserInterface $user, string $newHashedPassword): void
    {
        if (!$user instanceof Utilisateur) {
            throw new UnsupportedUserException(sprintf('Instances of "%s" are not supported.', $user::class));
        }

        $user->setPassword($newHashedPassword);
        $this->getEntityManager()->persist($user);
        $this->getEntityManager()->flush();
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

/**
     * Recherche + filtre par groupe pour l'admin
     */
    public function findByFiltresAdmin(?string $search, ?int $groupeId): array
    {
        $qb = $this->createQueryBuilder('u')
            ->leftJoin('u.utilisateurGroupe', 'g')
            ->addSelect('g')
            ->orderBy('u.dateInscription', 'DESC');

        if ($groupeId) {
            $qb->andWhere('g.id = :groupeId')
            ->setParameter('groupeId', $groupeId);
        }

        if ($search) {
            $qb->andWhere(
                $qb->expr()->orX(
                    $qb->expr()->like('u.nom', ':search'),
                    $qb->expr()->like('u.prenom', ':search'),
                    $qb->expr()->like('u.email', ':search')
                )
            )->setParameter('search', '%' . $search . '%');
        }

        return $qb->getQuery()->getResult();
    }
}
