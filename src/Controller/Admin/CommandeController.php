<?php

namespace App\Controller\Admin;

use App\Entity\Commande;
use App\Repository\CommandeRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/admin/commandes', name: 'admin_commande_')]
#[IsGranted('ROLE_ADMIN')]
class CommandeController extends AbstractController
{
    // ===== LISTING =====
    #[Route('', name: 'index', methods: ['GET'])]
    public function index(
        CommandeRepository $commandeRepository,
        Request $request
    ): Response {
        $search  = trim((string) $request->query->get('search', '')) ?: null;
        $statut  = $request->query->get('statut', '') ?: null;

        $commandes = $commandeRepository->findByFiltresAdmin($search, $statut);

        return $this->render('admin/commande/index.html.twig', [
            'commandes'     => $commandes,
            'search'        => $search,
            'statutActif'   => $statut,
            'statuts'       => ['en_cours', 'validee', 'payee', 'livree', 'annulee'],
        ]);
    }

    // ===== VOIR UNE COMMANDE =====
    #[Route('/{id}', name: 'show', methods: ['GET'], requirements: ['id' => '\d+'])]
    public function show(Commande $commande): Response
    {
        return $this->render('admin/commande/show.html.twig', [
            'commande' => $commande,
        ]);
    }

    // ===== CHANGER LE STATUT =====
    #[Route('/{id}/statut', name: 'statut', methods: ['POST'], requirements: ['id' => '\d+'])]
    public function changerStatut(
        Request $request,
        Commande $commande,
        EntityManagerInterface $entityManager
    ): Response {
        if ($this->isCsrfTokenValid('statut' . $commande->getId(), $request->getPayload()->getString('_token'))) {
            $nouveauStatut = $request->request->get('statut');
            $statutsValides = ['en_cours', 'validee', 'payee', 'livree', 'annulee'];
            if (in_array($nouveauStatut, $statutsValides)) {
                $commande->setStatut($nouveauStatut);
                $entityManager->flush();
                $this->addFlash('success', 'Statut mis à jour.');
            }
        }
        return $this->redirectToRoute('admin_commande_show', ['id' => $commande->getId()]);
    }

    // ===== SUPPRIMER UNE COMMANDE =====
    #[Route('/{id}/delete', name: 'delete', methods: ['POST'], requirements: ['id' => '\d+'])]
    public function delete(
        Request $request,
        Commande $commande,
        EntityManagerInterface $entityManager
    ): Response {
        if ($this->isCsrfTokenValid('delete' . $commande->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($commande);
            $entityManager->flush();
            $this->addFlash('success', 'Commande supprimée.');
        }
        return $this->redirectToRoute('admin_commande_index');
    }
}
