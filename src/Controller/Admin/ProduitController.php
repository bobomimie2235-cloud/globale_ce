<?php

namespace App\Controller\Admin;

use App\Entity\Produit;
use App\Form\ProduitType;
use App\Repository\DepartementRepository;
use App\Repository\ProduitCategorieRepository;
use App\Repository\ProduitRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Component\String\Slugger\SluggerInterface;

#[Route('/admin/produits', name: 'admin_produit_')]
#[IsGranted('ROLE_ADMIN')]
class ProduitController extends AbstractController
{
    // ===== LISTING =====
    #[Route('', name: 'index', methods: ['GET'])]
    public function index(
        ProduitRepository $produitRepository,
        ProduitCategorieRepository $produitCategorieRepository,
        DepartementRepository $departementRepository,
        Request $request
    ): Response {
        $search         = trim((string) $request->query->get('search', '')) ?: null;
        $categorieIds   = array_filter(array_map('intval', $request->query->all('categories')));
        $departementIds = array_filter(array_map('intval', $request->query->all('departements')));

        $produits = $produitRepository->findByFiltres(
            array_values($categorieIds),
            array_values($departementIds),
            $search
        );

        return $this->render('admin/produit/index.html.twig', [
            'produits'           => $produits,
            'categories'         => $produitCategorieRepository->findAll(),
            'departements'       => $departementRepository->findAll(),
            'categoriesActives'  => $categorieIds,
            'departementsActifs' => $departementIds,
            'search'             => $search,
        ]);
    }

    // ===== NOUVEAU PRODUIT =====
    #[Route('/new', name: 'new', methods: ['GET', 'POST'])]
    public function new(
        Request $request,
        EntityManagerInterface $entityManager,
        SluggerInterface $slugger
    ): Response {
        $produit = new Produit();
        $form    = $this->createForm(ProduitType::class, $produit);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $logoFile = $form->get('logo')->getData();
            if ($logoFile) {
                $safeFilename = $slugger->slug(pathinfo($logoFile->getClientOriginalName(), PATHINFO_FILENAME));
                $newFilename  = $safeFilename . '-' . uniqid() . '.' . $logoFile->guessExtension();
                $logoFile->move($this->getParameter('logos_directory'), $newFilename);
                $produit->setLogo($newFilename);
            }
            $entityManager->persist($produit);
            $entityManager->flush();
            $this->addFlash('success', 'Produit ajouté avec succès !');
            return $this->redirectToRoute('admin_produit_index');
        }

        return $this->render('admin/produit/new.html.twig', [
            'produit' => $produit,
            'form'    => $form,
        ]);
    }

    // ===== VOIR UN PRODUIT =====
    #[Route('/{id}', name: 'show', methods: ['GET'], requirements: ['id' => '\d+'])]
    public function show(Produit $produit): Response
    {
        return $this->render('admin/produit/show.html.twig', [
            'produit' => $produit,
        ]);
    }

    // ===== MODIFIER UN PRODUIT =====
    #[Route('/{id}/edit', name: 'edit', methods: ['GET', 'POST'], requirements: ['id' => '\d+'])]
    public function edit(
        Request $request,
        Produit $produit,
        EntityManagerInterface $entityManager,
        SluggerInterface $slugger
    ): Response {
        $form = $this->createForm(ProduitType::class, $produit);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $logoFile = $form->get('logo')->getData();
            if ($logoFile) {
                $safeFilename = $slugger->slug(pathinfo($logoFile->getClientOriginalName(), PATHINFO_FILENAME));
                $newFilename  = $safeFilename . '-' . uniqid() . '.' . $logoFile->guessExtension();
                $logoFile->move($this->getParameter('logos_directory'), $newFilename);
                $produit->setLogo($newFilename);
            }
            $entityManager->flush();
            $this->addFlash('success', 'Produit modifié avec succès !');
            return $this->redirectToRoute('admin_produit_index');
        }

        return $this->render('admin/produit/edit.html.twig', [
            'produit' => $produit,
            'form'    => $form,
        ]);
    }

    // ===== SUPPRIMER UN PRODUIT =====
    #[Route('/{id}/delete', name: 'delete', methods: ['POST'], requirements: ['id' => '\d+'])]
    public function delete(
        Request $request,
        Produit $produit,
        EntityManagerInterface $entityManager
    ): Response {
        if ($this->isCsrfTokenValid('delete' . $produit->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($produit);
            $entityManager->flush();
            $this->addFlash('success', 'Produit supprimé.');
        }
        return $this->redirectToRoute('admin_produit_index');
    }
}
