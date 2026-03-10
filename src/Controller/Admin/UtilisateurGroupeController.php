<?php

namespace App\Controller\Admin;

use App\Entity\UtilisateurGroupe;
use App\Form\UtilisateurGroupeType;
use App\Repository\UtilisateurGroupeRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/admin/groupes', name: 'admin_groupe_')]
#[IsGranted('ROLE_ADMIN')]
class UtilisateurGroupeController extends AbstractController
{
    #[Route('', name: 'index', methods: ['GET'])]
    public function index(
        UtilisateurGroupeRepository $utilisateurGroupeRepository,
        Request $request
    ): Response {
        $search = trim((string) $request->query->get('search', '')) ?: null;

        $groupes = $search
            ? $utilisateurGroupeRepository->findBySearch($search)
            : $utilisateurGroupeRepository->findAll();

        return $this->render('admin/groupe/index.html.twig', [
            'groupes' => $groupes,
            'search'  => $search,
        ]);
    }

    #[Route('/new', name: 'new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $groupe = new UtilisateurGroupe();
        $form   = $this->createForm(UtilisateurGroupeType::class, $groupe);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($groupe);
            $entityManager->flush();
            $this->addFlash('success', 'Groupe créé avec succès.');
            return $this->redirectToRoute('admin_groupe_index');
        }

        return $this->render('admin/groupe/new.html.twig', [
            'groupe' => $groupe,
            'form'   => $form,
        ]);
    }

    #[Route('/{id}', name: 'show', methods: ['GET'], requirements: ['id' => '\d+'])]
    public function show(UtilisateurGroupe $groupe): Response
    {
        return $this->render('admin/groupe/show.html.twig', [
            'groupe' => $groupe,
        ]);
    }

    #[Route('/{id}/edit', name: 'edit', methods: ['GET', 'POST'], requirements: ['id' => '\d+'])]
    public function edit(Request $request, UtilisateurGroupe $groupe, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(UtilisateurGroupeType::class, $groupe);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();
            $this->addFlash('success', 'Groupe modifié avec succès.');
            return $this->redirectToRoute('admin_groupe_show', ['id' => $groupe->getId()]);
        }

        return $this->render('admin/groupe/edit.html.twig', [
            'groupe' => $groupe,
            'form'   => $form,
        ]);
    }

    #[Route('/{id}/delete', name: 'delete', methods: ['POST'], requirements: ['id' => '\d+'])]
    public function delete(Request $request, UtilisateurGroupe $groupe, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete' . $groupe->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($groupe);
            $entityManager->flush();
            $this->addFlash('success', 'Groupe supprimé.');
        }
        return $this->redirectToRoute('admin_groupe_index');
    }
}
