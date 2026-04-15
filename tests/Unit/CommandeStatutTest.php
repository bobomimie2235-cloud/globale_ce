<?php

namespace App\Tests\Unit;

use App\Entity\Commande;
use PHPUnit\Framework\TestCase;

/**
 * ❌ TEST 3 — Validation du statut de l'entité Commande (TEST EN DÉFAUT)
 *
 * Objectif : vérifier que l'entité Commande contrôle que le statut
 * ne peut être que l'une des valeurs autorisées du cycle de vie
 * d'une commande : 'en_cours', 'payee', 'annulee', 'remboursee'.
 *
 * PROBLÈME DÉTECTÉ : setStatut() accepte n'importe quelle chaîne sans
 * validation. Un statut invalide comme 'SHIPPED' ou 'zombie' peut être
 * persisté en base, rendant les filtres admin (findByFiltresAdmin) et
 * l'affichage de l'historique utilisateur incohérents.
 *
 * Conséquence concrète : findByFiltresAdmin() filtre par statut exact.
 * Si une commande a le statut 'Payee' (majuscule) au lieu de 'payee',
 * elle n'apparaîtra jamais dans les résultats du filtre admin.
 *
 * CORRECTION : ajouter une constante et une validation dans setStatut() :
 *
 *   public const STATUTS_VALIDES = ['en_cours', 'payee', 'annulee', 'remboursee'];
 *
 *   public function setStatut(string $statut): static
 *   {
 *       if (!in_array($statut, self::STATUTS_VALIDES, true)) {
 *           throw new \InvalidArgumentException(
 *               sprintf('Statut invalide "%s". Valeurs autorisées : %s',
 *                   $statut, implode(', ', self::STATUTS_VALIDES))
 *           );
 *       }
 *       $this->statut = $statut;
 *       return $this;
 *   }
 *
 * Fichier : tests/Unit/CommandeStatutTest.php
 */
class CommandeStatutTest extends TestCase
{
    /**
     * ✅ Passe — le constructeur initialise bien les valeurs par défaut.
     */
    public function testConstructorSetsDefaultValues(): void
    {
        $commande = new Commande();

        $this->assertSame('en_cours', $commande->getStatut());
        $this->assertSame('0.00', $commande->getTotalTTC());
        $this->assertInstanceOf(\DateTime::class, $commande->getDateCommande());
    }

    /**
     * ✅ Passe — setStatut() accepte les valeurs valides attendues.
     */
    public function testSetStatutAcceptsValidValues(): void
    {
        $commande = new Commande();

        $commande->setStatut('payee');
        $this->assertSame('payee', $commande->getStatut());

        $commande->setStatut('annulee');
        $this->assertSame('annulee', $commande->getStatut());
    }

    /**
     * ✅ Passe — setTotalTTC() et getTotalTTC() fonctionnent correctement.
     */
    public function testSetAndGetTotalTTC(): void
    {
        $commande = new Commande();
        $commande->setTotalTTC('149.99');

        $this->assertSame('149.99', $commande->getTotalTTC());
    }

    /**
     * ❌ ÉCHOUE — setStatut() n'effectue aucune validation.
     *
     * Ce test vérifie qu'un statut invalide lève une \InvalidArgumentException.
     * Il échoue car setStatut() accepte n'importe quelle chaîne sans contrôle.
     *
     * Résultat attendu : exception \InvalidArgumentException
     * Résultat obtenu  : aucune exception, la valeur invalide est stockée
     *
     * Erreur PHPUnit :
     * Failed asserting that exception of type "InvalidArgumentException" is thrown.
     */
    public function testSetStatutRejectsInvalidValue(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('Statut invalide');

        $commande = new Commande();

        // ÉCHOUE : aucune exception n'est levée, 'zombie' est accepté
        $commande->setStatut('zombie');
    }

    /**
     * ❌ ÉCHOUE — setStatut() n'effectue aucune validation.
     *
     * Un statut avec une casse incorrecte ('Payee' au lieu de 'payee')
     * devrait être rejeté pour éviter les incohérences avec findByFiltresAdmin().
     */
    public function testSetStatutRejectsCaseMismatch(): void
    {
        $this->expectException(\InvalidArgumentException::class);

        $commande = new Commande();

        // ÉCHOUE : 'Payee' est accepté alors qu'il devrait être rejeté
        $commande->setStatut('Payee');
    }
}
