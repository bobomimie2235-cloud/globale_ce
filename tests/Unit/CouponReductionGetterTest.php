<?php

namespace App\Tests\Unit;

use App\Entity\CouponReduction;
use PHPUnit\Framework\TestCase;

/**
 * ✅ TEST 1 — Getters / Setters de l'entité CouponReduction (TEST QUI PASSE)
 *
 * Objectif : vérifier que les getters et setters de l'entité CouponReduction
 * retournent et stockent correctement les valeurs, et que isActif() reflète
 * bien l'état du coupon.
 *
 * Fichier : tests/Unit/CouponReductionGetterTest.php
 */
class CouponReductionGetterTest extends TestCase
{
    private CouponReduction $coupon;

    protected function setUp(): void
    {
        $this->coupon = new CouponReduction();
        $this->coupon->setReference('NOEL2025');
        $this->coupon->setIntitule('Réduction Noël 2025');
        $this->coupon->setActif(true);
        $this->coupon->setAdresse('12 rue de la Paix');
        $this->coupon->setVille('Nantes');
        $this->coupon->setOffreCommerciale('20% de réduction sur tout le magasin');
    }

    /**
     * Vérifie que getReference() retourne la référence définie.
     */
    public function testGetReferenceReturnsCorrectValue(): void
    {
        $this->assertSame('NOEL2025', $this->coupon->getReference());
    }

    /**
     * Vérifie que getIntitule() retourne l'intitulé défini.
     */
    public function testGetIntituleReturnsCorrectValue(): void
    {
        $this->assertSame('Réduction Noël 2025', $this->coupon->getIntitule());
    }

    /**
     * Vérifie que isActif() retourne true quand le coupon est actif.
     */
    public function testIsActifReturnsTrueWhenActive(): void
    {
        $this->assertTrue($this->coupon->isActif());
    }

    /**
     * Vérifie que isActif() retourne false quand le coupon est désactivé.
     */
    public function testIsActifReturnsFalseWhenInactive(): void
    {
        $this->coupon->setActif(false);
        $this->assertFalse($this->coupon->isActif());
    }

    /**
     * Vérifie que getVille() retourne la ville définie.
     */
    public function testGetVilleReturnsCorrectValue(): void
    {
        $this->assertSame('Nantes', $this->coupon->getVille());
    }

    /**
     * Vérifie que getOffreCommerciale() retourne l'offre définie.
     */
    public function testGetOffreCommercialeReturnsCorrectValue(): void
    {
        $this->assertSame('20% de réduction sur tout le magasin', $this->coupon->getOffreCommerciale());
    }

    /**
     * Vérifie que getId() retourne null avant persistance en base.
     */
    public function testGetIdIsNullBeforePersist(): void
    {
        $this->assertNull($this->coupon->getId());
    }

    /**
     * Vérifie que getUtilisateurCoupons() retourne une collection vide à la construction.
     */
    public function testGetUtilisateurCouponsIsEmptyOnConstruct(): void
    {
        $fresh = new CouponReduction();
        $this->assertCount(0, $fresh->getUtilisateurCoupons());
    }
}
