<?php

namespace App\Controller\Admin;

use App\Entity\Utilisateur;
use App\Form\ProfilType;
use App\Form\AdminUtilisateurType;
use App\Repository\UtilisateurGroupeRepository;
use App\Repository\UtilisateurRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/admin/utilisateurs', name: 'admin_utilisateur_')]
#[IsGranted('ROLE_ADMIN')]
class UtilisateurController extends AbstractController
{
    // ===== LISTING =====
    #[Route('', name: 'index', methods: ['GET'])]
    public function index(
        UtilisateurRepository $utilisateurRepository,
        UtilisateurGroupeRepository $utilisateurGroupeRepository,
        Request $request
    ): Response {
        $search  = trim((string) $request->query->get('search', '')) ?: null;
        $groupeId = (int) $request->query->get('groupe', 0) ?: null;

        $utilisateurs = $utilisateurRepository->findByFiltresAdmin($search, $groupeId);

        return $this->render('admin/utilisateur/index.html.twig', [
            'utilisateurs' => $utilisateurs,
            'groupes'      => $utilisateurGroupeRepository->findAll(),
            'search'       => $search,
            'groupeActif'  => $groupeId,
        ]);
    }

    // ===== VOIR UN UTILISATEUR =====
    #[Route('/{id}', name: 'show', methods: ['GET'], requirements: ['id' => '\d+'])]
    public function show(Utilisateur $utilisateur): Response
    {
        return $this->render('admin/utilisateur/show.html.twig', [
            'utilisateur' => $utilisateur,
        ]);
    }

    // ===== MODIFIER UN UTILISATEUR =====
    #[Route('/{id}/edit', name: 'edit', methods: ['GET', 'POST'], requirements: ['id' => '\d+'])]
    public function edit(
        Request $request,
        Utilisateur $utilisateur,
        EntityManagerInterface $entityManager
    ): Response {
        $form = $this->createForm(AdminUtilisateurType::class, $utilisateur); // ← AdminUtilisateurType
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();
            $this->addFlash('success', 'Utilisateur mis à jour.');
            return $this->redirectToRoute('admin_utilisateur_index');
        }

        return $this->render('admin/utilisateur/edit.html.twig', [
            'utilisateur' => $utilisateur,
            'form'        => $form,
        ]);
    }

    // ===== SUPPRIMER UN UTILISATEUR =====
    #[Route('/{id}/delete', name: 'delete', methods: ['POST'], requirements: ['id' => '\d+'])]
    public function delete(
        Request $request,
        Utilisateur $utilisateur,
        EntityManagerInterface $entityManager
    ): Response {
        if ($this->isCsrfTokenValid('delete' . $utilisateur->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($utilisateur);
            $entityManager->flush();
            $this->addFlash('success', 'Utilisateur supprimé.');
        }
        return $this->redirectToRoute('admin_utilisateur_index');
    }
}
