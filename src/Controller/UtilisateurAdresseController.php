<?php

namespace App\Controller;

use App\Entity\UtilisateurAdresse;
use App\Form\UtilisateurAdresseType;
use App\Repository\UtilisateurAdresseRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/utilisateur/adresse')]
final class UtilisateurAdresseController extends AbstractController
{
    #[Route(name: 'app_utilisateur_adresse_index', methods: ['GET'])]
    public function index(UtilisateurAdresseRepository $utilisateurAdresseRepository): Response
    {
        return $this->render('utilisateur_adresse/index.html.twig', [
            'utilisateur_adresses' => $utilisateurAdresseRepository->findAll(),
        ]);
    }

    #[IsGranted('VIEW', subject: 'utilisateurAdresse')]
    #[Route('/new', name: 'app_utilisateur_adresse_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $utilisateurAdresse = new UtilisateurAdresse();
        $form = $this->createForm(UtilisateurAdresseType::class, $utilisateurAdresse);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($utilisateurAdresse);
            $entityManager->flush();

            $this->addFlash('success', 'Adresse ajoutée avec succès !');
            return $this->redirectToRoute('app_utilisateur_adresse_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('utilisateur_adresse/new.html.twig', [
            'utilisateur_adresse' => $utilisateurAdresse,
            'form' => $form,
        ]);
    }

    #[IsGranted('VIEW', subject: 'utilisateurAdresse')]
    #[Route('/{id}', name: 'app_utilisateur_adresse_show', methods: ['GET'])]
    public function show(UtilisateurAdresse $utilisateurAdresse): Response
    {
        return $this->render('utilisateur_adresse/show.html.twig', [
            'utilisateur_adresse' => $utilisateurAdresse,
        ]);
    }

    #[IsGranted('VIEW', subject: 'utilisateurAdresse')]
    #[Route('/{id}/edit', name: 'app_utilisateur_adresse_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, UtilisateurAdresse $utilisateurAdresse, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(UtilisateurAdresseType::class, $utilisateurAdresse);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_utilisateur_adresse_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('utilisateur_adresse/edit.html.twig', [
            'utilisateur_adresse' => $utilisateurAdresse,
            'form' => $form,
        ]);
    }

    #[IsGranted('VIEW', subject: 'utilisateurAdresse')]
    #[Route('/{id}', name: 'app_utilisateur_adresse_delete', methods: ['POST'])]
    public function delete(Request $request, UtilisateurAdresse $utilisateurAdresse, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$utilisateurAdresse->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($utilisateurAdresse);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_utilisateur_adresse_index', [], Response::HTTP_SEE_OTHER);
    }
}
