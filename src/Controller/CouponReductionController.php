<?php

namespace App\Controller;

use App\Entity\CouponReduction;
use App\Form\CouponReductionType;
use App\Repository\CouponReductionRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/coupon/reduction')]
final class CouponReductionController extends AbstractController
{
    #[Route(name: 'app_coupon_reduction_index', methods: ['GET'])]
    public function index(CouponReductionRepository $couponReductionRepository): Response
    {
        return $this->render('coupon_reduction/index.html.twig', [
            'coupon_reductions' => $couponReductionRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_coupon_reduction_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $couponReduction = new CouponReduction();
        $form = $this->createForm(CouponReductionType::class, $couponReduction);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($couponReduction);
            $entityManager->flush();

            return $this->redirectToRoute('app_coupon_reduction_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('coupon_reduction/new.html.twig', [
            'coupon_reduction' => $couponReduction,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_coupon_reduction_show', methods: ['GET'])]
    public function show(CouponReduction $couponReduction): Response
    {
        return $this->render('coupon_reduction/show.html.twig', [
            'coupon_reduction' => $couponReduction,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_coupon_reduction_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, CouponReduction $couponReduction, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(CouponReductionType::class, $couponReduction);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_coupon_reduction_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('coupon_reduction/edit.html.twig', [
            'coupon_reduction' => $couponReduction,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_coupon_reduction_delete', methods: ['POST'])]
    public function delete(Request $request, CouponReduction $couponReduction, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$couponReduction->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($couponReduction);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_coupon_reduction_index', [], Response::HTTP_SEE_OTHER);
    }
}
