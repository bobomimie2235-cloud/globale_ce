<?php

namespace App\Controller\Admin;

use App\Entity\CouponReduction;
use App\Form\CouponReductionType;
use App\Repository\CouponReductionRepository;
use App\Repository\CouponCategorieRepository;
use App\Repository\DepartementRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Component\String\Slugger\SluggerInterface;

#[Route('/admin/coupons', name: 'admin_coupon_')]
#[IsGranted('ROLE_ADMIN')]
class CouponReductionController extends AbstractController
{
    // ===== LISTING =====
    #[Route('', name: 'index', methods: ['GET'])]
    public function index(
        CouponReductionRepository $couponReductionRepository,
        CouponCategorieRepository $couponCategorieRepository,
        DepartementRepository $departementRepository,
        Request $request
    ): Response {
        $search      = trim((string) $request->query->get('search', '')) ?: null;
        $categorieId = $request->query->get('categorie', '') ?: null;
        $deptId      = $request->query->get('departement', '') ?: null;
        $actif       = $request->query->get('actif', '') ?: null;

        $coupons = $couponReductionRepository->findByFiltresAdmin(
            $search,
            $categorieId ? (int)$categorieId : null,
            $deptId ? (int)$deptId : null,
            $actif !== null && $actif !== '' ? (bool)$actif : null
        );

        return $this->render('admin/coupon/index.html.twig', [
            'coupons'        => $coupons,
            'categories'     => $couponCategorieRepository->findAll(),
            'departements'   => $departementRepository->findAll(),
            'search'         => $search,
            'categorieActif' => $categorieId,
            'deptActif'      => $deptId,
            'actifActif'     => $actif,
        ]);
    }

    // ===== NOUVEAU =====
    #[Route('/new', name: 'new', methods: ['GET', 'POST'])]
    public function new(
        Request $request,
        EntityManagerInterface $entityManager,
        SluggerInterface $slugger
    ): Response {
        $coupon = new CouponReduction();
        $form   = $this->createForm(CouponReductionType::class, $coupon);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->handleLogo($form, $coupon, $slugger);
            $entityManager->persist($coupon);
            $entityManager->flush();
            $this->addFlash('success', 'Coupon créé avec succès.');
            return $this->redirectToRoute('admin_coupon_index');
        }

        return $this->render('admin/coupon/new.html.twig', [
            'coupon' => $coupon,
            'form'   => $form,
        ]);
    }

        // ===== VOIR =====
    #[Route('/{id}', name: 'show', methods: ['GET'], requirements: ['id' => '\d+'])]
    public function show(CouponReduction $coupon): Response
    {
        return $this->render('admin/coupon/show.html.twig', [
            'coupon' => $coupon,
        ]);
    }

    // ===== MODIFIER =====
    #[Route('/{id}/edit', name: 'edit', methods: ['GET', 'POST'], requirements: ['id' => '\d+'])]
    public function edit(
        Request $request,
        CouponReduction $coupon,
        EntityManagerInterface $entityManager,
        SluggerInterface $slugger
    ): Response {
        $form = $this->createForm(CouponReductionType::class, $coupon);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->handleLogo($form, $coupon, $slugger);
            $entityManager->flush();
            $this->addFlash('success', 'Coupon modifié avec succès.');
            return $this->redirectToRoute('admin_coupon_show', ['id' => $coupon->getId()]);
        }

            if ($form->isSubmitted() && !$form->isValid()) {
    dd($form->getErrors(true, true));
            }

        return $this->render('admin/coupon/edit.html.twig', [
            'coupon' => $coupon,
            'form'   => $form,
        ]);
    }

    // ===== SUPPRIMER =====
    #[Route('/{id}/delete', name: 'delete', methods: ['POST'], requirements: ['id' => '\d+'])]
    public function delete(
        Request $request,
        CouponReduction $coupon,
        EntityManagerInterface $entityManager
    ): Response {
        if ($this->isCsrfTokenValid('delete' . $coupon->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($coupon);
            $entityManager->flush();
            $this->addFlash('success', 'Coupon supprimé.');
        }
        return $this->redirectToRoute('admin_coupon_index');
    }

    // ===== HELPER logo =====
    private function handleLogo($form, CouponReduction $coupon, SluggerInterface $slugger): void
    {
        $logoFile = $form->get('logo')->getData();
        if ($logoFile) {
            $safeName    = $slugger->slug(pathinfo($logoFile->getClientOriginalName(), PATHINFO_FILENAME));
            $newFilename = $safeName . '-' . uniqid() . '.' . $logoFile->guessExtension();
            $logoFile->move($this->getParameter('logos_directory'), $newFilename);
            $coupon->setLogo($newFilename);
        }
    }
}
