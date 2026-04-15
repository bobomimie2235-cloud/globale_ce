<?php

namespace App\Tests\Unit;

use App\Entity\CouponReduction;
use App\Entity\Utilisateur;
use App\Entity\UtilisateurCoupon;
use App\Repository\UtilisateurCouponRepository;
use PHPUnit\Framework\TestCase;

/**
 * ❌ TEST 2 — UtilisateurCouponRepository::findBySearch() (TEST EN DÉFAUT)
 *
 * Objectif : vérifier que la méthode findBySearch() du repository
 * interroge bien les champs de l'entité UtilisateurCoupon.
 *
 * PROBLÈME DÉTECTÉ : findBySearch() a été copiée-collée depuis
 * UtilisateurGroupeRepository sans être adaptée. Elle interroge
 * des champs inexistants dans UtilisateurCoupon :
 *   - g.nomGroupe        → n'existe pas dans UtilisateurCoupon
 *   - g.referenceGroupe  → n'existe pas dans UtilisateurCoupon
 *   - g.ville            → n'existe pas dans UtilisateurCoupon
 *   - g.email            → n'existe pas dans UtilisateurCoupon
 *
 * En production, tout appel à findBySearch() lèverait une erreur Doctrine :
 * "[Semantical Error] ... 'nomGroupe' does not exist on App\Entity\UtilisateurCoupon"
 *
 * CORRECTION : supprimer findBySearch() du repository UtilisateurCoupon
 * (elle n'a pas de sens ici) ou la réécrire pour interroger les champs
 * réels de UtilisateurCoupon via ses relations :
 *
 *   public function findByUtilisateur(Utilisateur $utilisateur): array
 *   {
 *       return $this->createQueryBuilder('uc')
 *           ->where('uc.utilisateur = :utilisateur')
 *           ->setParameter('utilisateur', $utilisateur)
 *           ->orderBy('uc.dateUtilisation', 'DESC')
 *           ->getQuery()
 *           ->getResult();
 *   }
 *
 * Fichier : tests/Unit/UtilisateurCouponRepositoryTest.php
 */
class UtilisateurCouponRepositoryTest extends TestCase
{

    /**
     * ✅ Passe — vérifie que l'entité UtilisateurCoupon possède bien
     * les propriétés réelles utilisées dans les relations.
     */
    public function testUtilisateurCouponHasCorrectProperties(): void
    {
        $reflection = new \ReflectionClass(UtilisateurCoupon::class);
        $properties = array_map(fn($p) => $p->getName(), $reflection->getProperties());

        // Propriétés qui DOIVENT exister dans UtilisateurCoupon
        $this->assertContains('utilise',          $properties);
        $this->assertContains('dateUtilisation',  $properties);
        $this->assertContains('utilisateur',      $properties);
        $this->assertContains('couponReduction',  $properties);
    }

    public function testFindByUtilisateurMethodExists(): void
{
    $this->assertTrue(
        method_exists(UtilisateurCouponRepository::class, 'findByUtilisateur'),
        'La méthode findByUtilisateur() doit exister dans UtilisateurCouponRepository.'
    );
}

    /**
     * ✅ Passe — vérifie que isUtilise() et setUtilise() fonctionnent
     * correctement sur l'entité (comportement de base non affecté par le bug).
     */
    public function testUtilisateurCouponIsUtiliseDefaultFalse(): void
    {
        $utilisateurCoupon = new UtilisateurCoupon();
        $this->assertFalse($utilisateurCoupon->isUtilise());
    }

    /**
     * ✅ Passe — vérifie que setUtilise(true) + setDateUtilisation()
     * fonctionnent comme dans le contrôleur utiliserMonCoupon().
     */
    public function testMarkAsUsedWorksProperly(): void
    {
        $utilisateurCoupon = new UtilisateurCoupon();
        $utilisateurCoupon->setUtilise(true);
        $utilisateurCoupon->setDateUtilisation(new \DateTimeImmutable());

        $this->assertTrue($utilisateurCoupon->isUtilise());
        $this->assertInstanceOf(\DateTimeImmutable::class, $utilisateurCoupon->getDateUtilisation());
    }
}
