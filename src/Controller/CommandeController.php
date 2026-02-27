<?php

namespace App\Controller;

use App\Entity\Commande;
use App\Entity\CommandeProduit;
use App\Entity\Utilisateur;
use App\Repository\CommandeRepository;
use App\Repository\ProduitRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/commande')]
final class CommandeController extends AbstractController
{
    #[Route(name: 'app_commande_index', methods: ['GET'])]
    public function index(CommandeRepository $commandeRepository): Response
    {
        /** @var Utilisateur $user */
        $user = $this->getUser();

        // Admin voit toutes les commandes, user voit les siennes
        if ($this->isGranted('ROLE_ADMIN')) {
            $commandes = $commandeRepository->findAll();
        } else {
            $commandes = $commandeRepository->findBy(['utilisateur' => $user]);
        }

        return $this->render('commande/index.html.twig', [
            'commandes' => $commandes,
        ]);
    }

    // ===== Panier — affiche les produits disponibles =====
    #[IsGranted('ROLE_USER')]
    #[Route('/panier', name: 'app_commande_panier', methods: ['GET'])]
    public function panier(ProduitRepository $produitRepository): Response
    {
        return $this->render('commande/panier.html.twig', [
            'produits' => $produitRepository->findAll(),
        ]);
    }

    // ===== Ajouter un produit à la commande en cours =====
    #[IsGranted('ROLE_USER')]
    #[Route('/ajouter/{produitId}', name: 'app_commande_ajouter_produit', methods: ['POST'])]
    public function ajouterProduit(
        int $produitId,
        Request $request,
        EntityManagerInterface $entityManager,
        ProduitRepository $produitRepository,
        CommandeRepository $commandeRepository
    ): Response {
        /** @var Utilisateur $user */
        $user = $this->getUser();

        $produit = $produitRepository->find($produitId);

        if (!$produit) {
            $this->addFlash('error', 'Produit introuvable.');
            return $this->redirectToRoute('app_commande_panier');
        }

        $quantite = (int) $request->request->get('quantite', 1);

        if ($quantite <= 0 || $quantite > $produit->getStock()) {
            $this->addFlash('error', 'Quantité invalide ou stock insuffisant.');
            return $this->redirectToRoute('app_commande_panier');
        }

        // Cherche une commande en cours pour cet utilisateur
        $commande = $commandeRepository->findOneBy([
            'utilisateur' => $user,
            'statut' => 'en_cours',
        ]);

        // Crée une nouvelle commande si aucune en cours
        if (!$commande) {
            $commande = new Commande();
            $commande->setUtilisateur($user);
            $entityManager->persist($commande);
        }

        // Vérifie si le produit est déjà dans la commande
        $commandeProduitExistant = null;
        foreach ($commande->getCommandeProduits() as $cp) {
            if ($cp->getProduit() === $produit) {
                $commandeProduitExistant = $cp;
                break;
            }
        }

        if ($commandeProduitExistant) {
            // Met à jour la quantité
            $commandeProduitExistant->setQuantite($commandeProduitExistant->getQuantite() + $quantite);
        } else {
            // Crée un nouveau CommandeProduit
            $commandeProduit = new CommandeProduit();
            $commandeProduit->setCommande($commande);
            $commandeProduit->setProduit($produit);
            $commandeProduit->setQuantite($quantite);
            $entityManager->persist($commandeProduit);
        }

        // Recalcule le total TTC
        $total = 0;
        foreach ($commande->getCommandeProduits() as $cp) {
            $total += $cp->getProduit()->getPrixPublic() * $cp->getQuantite();
        }
        // Ajoute le nouveau produit si pas encore persisté
        if (!$commandeProduitExistant) {
            $total += $produit->getPrixPublic() * $quantite;
        }
        $commande->setTotalTTC((string) $total);

        $entityManager->flush();

        $this->addFlash('success', 'Produit ajouté à votre commande.');
        return $this->redirectToRoute('app_commande_en_cours');
    }

    // ===== Commande en cours =====
    #[IsGranted('ROLE_USER')]
    #[Route('/en-cours', name: 'app_commande_en_cours', methods: ['GET'])]
    public function enCours(CommandeRepository $commandeRepository): Response
    {
        /** @var Utilisateur $user */
        $user = $this->getUser();

        $commande = $commandeRepository->findOneBy([
            'utilisateur' => $user,
            'statut' => 'en_cours',
        ]);

        return $this->render('commande/en_cours.html.twig', [
            'commande' => $commande,
        ]);
    }

    // ===== Valider la commande =====
    #[IsGranted('ROLE_USER')]
    #[Route('/valider', name: 'app_commande_valider', methods: ['POST'])]
    public function valider(
        Request $request,
        EntityManagerInterface $entityManager,
        CommandeRepository $commandeRepository
    ): Response {
        /** @var Utilisateur $user */
        $user = $this->getUser();

        $commande = $commandeRepository->findOneBy([
            'utilisateur' => $user,
            'statut' => 'en_cours',
        ]);

        if (!$commande || $commande->getCommandeProduits()->isEmpty()) {
            $this->addFlash('error', 'Votre commande est vide.');
            return $this->redirectToRoute('app_commande_en_cours');
        }

        if ($this->isCsrfTokenValid('valider_commande', $request->request->get('_token'))) {

            // Décrémente le stock de chaque produit
            foreach ($commande->getCommandeProduits() as $cp) {
                $produit = $cp->getProduit();
                $produit->setStock($produit->getStock() - $cp->getQuantite());
            }

            $commande->setStatut('validee');
            $commande->setDateCommande(new \DateTime());
            $entityManager->flush();

            $this->addFlash('success', 'Commande validée avec succès !');
        }

        return $this->redirectToRoute('app_commande_index');
    }

    // ===== Retirer un produit de la commande =====
    #[IsGranted('ROLE_USER')]
    #[Route('/retirer/{commandeProduitId}', name: 'app_commande_retirer_produit', methods: ['POST'])]
    public function retirerProduit(
        int $commandeProduitId,
        Request $request,
        EntityManagerInterface $entityManager
    ): Response {
        $commandeProduit = $entityManager->getRepository(CommandeProduit::class)->find($commandeProduitId);

        if ($commandeProduit && $commandeProduit->getCommande()->getUtilisateur() === $this->getUser()) {
            $commande = $commandeProduit->getCommande();
            $entityManager->remove($commandeProduit);

            // Recalcule le total
            $total = 0;
            foreach ($commande->getCommandeProduits() as $cp) {
                if ($cp !== $commandeProduit) {
                    $total += $cp->getProduit()->getPrixPublic() * $cp->getQuantite();
                }
            }
            $commande->setTotalTTC((string) $total);
            $entityManager->flush();

            $this->addFlash('success', 'Produit retiré de la commande.');
        }

        return $this->redirectToRoute('app_commande_en_cours');
    }

    #[Route('/{id}', name: 'app_commande_show', methods: ['GET'], requirements: ['id' => '\d+'])]
    public function show(Commande $commande): Response
    {
        return $this->render('commande/show.html.twig', [
            'commande' => $commande,
        ]);
    }

    #[IsGranted('ROLE_ADMIN')]
    #[Route('/{id}', name: 'app_commande_delete', methods: ['POST'], requirements: ['id' => '\d+'])]
    public function delete(Request $request, Commande $commande, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete' . $commande->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($commande);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_commande_index', [], Response::HTTP_SEE_OTHER);
    }
}