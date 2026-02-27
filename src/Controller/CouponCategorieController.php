<?php

namespace App\Controller;

use App\Entity\CouponCategorie;
use App\Form\CouponCategorieType;
use App\Repository\CouponCategorieRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/coupon/categorie')]
final class CouponCategorieController extends AbstractController
{
    #[Route(name: 'app_coupon_categorie_index', methods: ['GET'])]
    public function index(CouponCategorieRepository $couponCategorieRepository): Response
    {
        return $this->render('coupon_categorie/index.html.twig', [
            'coupon_categories' => $couponCategorieRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_coupon_categorie_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $couponCategorie = new CouponCategorie();
        $form = $this->createForm(CouponCategorieType::class, $couponCategorie);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($couponCategorie);
            $entityManager->flush();

            return $this->redirectToRoute('app_coupon_categorie_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('coupon_categorie/new.html.twig', [
            'coupon_categorie' => $couponCategorie,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_coupon_categorie_show', methods: ['GET'])]
    public function show(CouponCategorie $couponCategorie): Response
    {
        return $this->render('coupon_categorie/show.html.twig', [
            'coupon_categorie' => $couponCategorie,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_coupon_categorie_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, CouponCategorie $couponCategorie, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(CouponCategorieType::class, $couponCategorie);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_coupon_categorie_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('coupon_categorie/edit.html.twig', [
            'coupon_categorie' => $couponCategorie,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_coupon_categorie_delete', methods: ['POST'])]
    public function delete(Request $request, CouponCategorie $couponCategorie, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$couponCategorie->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($couponCategorie);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_coupon_categorie_index', [], Response::HTTP_SEE_OTHER);
    }
}
