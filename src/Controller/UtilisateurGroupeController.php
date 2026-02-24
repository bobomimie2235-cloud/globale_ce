<?php

namespace App\Controller;

use App\Entity\UtilisateurGroupe;
use App\Form\UtilisateurGroupeType;
use App\Repository\UtilisateurGroupeRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/utilisateur/groupe')]
final class UtilisateurGroupeController extends AbstractController
{
    #[Route(name: 'app_utilisateur_groupe_index', methods: ['GET'])]
    public function index(UtilisateurGroupeRepository $utilisateurGroupeRepository): Response
    {
        return $this->render('utilisateur_groupe/index.html.twig', [
            'utilisateur_groupes' => $utilisateurGroupeRepository->findAll(),
        ]);
    }

    #[IsGranted('ROLE_ADMIN')]
    #[Route('/new', name: 'app_utilisateur_groupe_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $utilisateurGroupe = new UtilisateurGroupe();
        $form = $this->createForm(UtilisateurGroupeType::class, $utilisateurGroupe);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($utilisateurGroupe);
            $entityManager->flush();

            $this->addFlash('success', 'Groupe ajouté avec succès !');

            return $this->redirectToRoute('app_utilisateur_groupe_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('utilisateur_groupe/new.html.twig', [
            'utilisateur_groupe' => $utilisateurGroupe,
            'form' => $form,
        ]);
    }

    #[IsGranted('ROLE_ADMIN')]
    #[Route('/{id}', name: 'app_utilisateur_groupe_show', methods: ['GET'])]
    public function show(UtilisateurGroupe $utilisateurGroupe): Response
    {
        return $this->render('utilisateur_groupe/show.html.twig', [
            'utilisateur_groupe' => $utilisateurGroupe,
        ]);
    }

    #[IsGranted('ROLE_ADMIN')]
    #[Route('/{id}/edit', name: 'app_utilisateur_groupe_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, UtilisateurGroupe $utilisateurGroupe, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(UtilisateurGroupeType::class, $utilisateurGroupe);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_utilisateur_groupe_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('utilisateur_groupe/edit.html.twig', [
            'utilisateur_groupe' => $utilisateurGroupe,
            'form' => $form,
        ]);
    }

    #[IsGranted('ROLE_ADMIN')]
    #[Route('/{id}', name: 'app_utilisateur_groupe_delete', methods: ['POST'])]
    public function delete(Request $request, UtilisateurGroupe $utilisateurGroupe, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$utilisateurGroupe->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($utilisateurGroupe);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_utilisateur_groupe_index', [], Response::HTTP_SEE_OTHER);
    }
}
