<?php

// Controller/Admin/CouponCategorieController

namespace App\Controller\Admin;

use App\Entity\CouponCategorie;
use App\Form\CouponCategorieType;
use App\Repository\CouponCategorieRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/admin/coupon-categories', name: 'admin_coupon_categorie_')]
#[IsGranted('ROLE_ADMIN')]
class CouponCategorieController extends AbstractController
{
    #[Route('', name: 'index', methods: ['GET'])]
    public function index(CouponCategorieRepository $couponCategorieRepository): Response
    {
        return $this->render('admin/coupon_categorie/index.html.twig', [
            'categories' => $couponCategorieRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $categorie = new CouponCategorie();
        $form      = $this->createForm(CouponCategorieType::class, $categorie);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($categorie);
            $entityManager->flush();
            $this->addFlash('success', 'Catégorie créée avec succès.');
            return $this->redirectToRoute('admin_coupon_categorie_index');
        }

        return $this->render('admin/coupon_categorie/new.html.twig', [
            'categorie' => $categorie,
            'form'      => $form,
        ]);
    }

    #[Route('/{id}', name: 'show', methods: ['GET'], requirements: ['id' => '\d+'])]
    public function show(CouponCategorie $categorie): Response
    {
        return $this->render('admin/coupon_categorie/show.html.twig', [
            'categorie' => $categorie,
        ]);
    }

    #[Route('/{id}/edit', name: 'edit', methods: ['GET', 'POST'], requirements: ['id' => '\d+'])]
    public function edit(Request $request, CouponCategorie $categorie, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(CouponCategorieType::class, $categorie);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();
            $this->addFlash('success', 'Catégorie modifiée avec succès.');
            return $this->redirectToRoute('admin_coupon_categorie_index');
        }

        return $this->render('admin/coupon_categorie/edit.html.twig', [
            'categorie' => $categorie,
            'form'      => $form,
        ]);
    }

    #[Route('/{id}/delete', name: 'delete', methods: ['POST'], requirements: ['id' => '\d+'])]
    public function delete(Request $request, CouponCategorie $categorie, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete' . $categorie->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($categorie);
            $entityManager->flush();
            $this->addFlash('success', 'Catégorie supprimée.');
        }
        return $this->redirectToRoute('admin_coupon_categorie_index');
    }
}
