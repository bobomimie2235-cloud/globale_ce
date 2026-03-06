<?php

namespace App\Controller;

use App\Entity\Utilisateur;
use App\Entity\CouponReduction;
use App\Form\CouponReductionType;
use App\Entity\UtilisateurCoupon;
use App\Repository\UtilisateurCouponRepository;
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

#[Route('/coupon/reduction')]
final class CouponReductionController extends AbstractController
{
    #[Route(name: 'app_coupon_reduction_index', methods: ['GET'])]
    public function index(
        CouponReductionRepository $couponReductionRepository,
        CouponCategorieRepository $couponCategorieRepository,
        DepartementRepository $departementRepository,
        Request $request
    ): Response {
        $categorieIds   = array_filter(array_map('intval', $request->query->all('categories')));
        $departementIds = array_filter(array_map('intval', $request->query->all('departements')));

        $coupon_reductions = $couponReductionRepository->findByFiltres(
            array_values($categorieIds),
            array_values($departementIds)
        );

        $templateData = [
            'coupon_reductions'  => $coupon_reductions,
            'categories'         => $couponCategorieRepository->findAll(),
            'departements'       => $departementRepository->findAll(),
            'categoriesActives'  => $categorieIds,
            'departementsActifs' => $departementIds,
        ];

        // Requête AJAX → fragment grille uniquement
        if ($request->headers->get('X-Requested-With') === 'XMLHttpRequest') {
            return $this->render('coupon_reduction/_grille.html.twig', $templateData);
        }

        return $this->render('coupon_reduction/index.html.twig', $templateData);
    }

    #[IsGranted('ROLE_ADMIN')]
    #[Route('/new', name: 'app_coupon_reduction_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager, SluggerInterface $slugger): Response
    {
        $couponReduction = new CouponReduction();
        $form = $this->createForm(CouponReductionType::class, $couponReduction);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $logoFile = $form->get('logo')->getData();
            if ($logoFile) {
                $safeFilename = $slugger->slug(pathinfo($logoFile->getClientOriginalName(), PATHINFO_FILENAME));
                $newFilename = $safeFilename . '-' . uniqid() . '.' . $logoFile->guessExtension();
                $logoFile->move($this->getParameter('logos_directory'), $newFilename);
                $couponReduction->setLogo($newFilename);
            }

            $entityManager->persist($couponReduction);
            $entityManager->flush();
            $this->addFlash('success', 'Coupon réduction ajouté avec succès !');

            return $this->redirectToRoute('app_coupon_reduction_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('coupon_reduction/new.html.twig', [
            'coupon_reduction' => $couponReduction,
            'form'             => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_coupon_reduction_show', methods: ['GET'], requirements: ['id' => '\d+'])]
    public function show(CouponReduction $couponReduction): Response
    {
        return $this->render('coupon_reduction/show.html.twig', [
            'coupon_reduction' => $couponReduction,
        ]);
    }

    #[IsGranted('ROLE_ADMIN')]
    #[Route('/{id}/edit', name: 'app_coupon_reduction_edit', methods: ['GET', 'POST'], requirements: ['id' => '\d+'])]
    public function edit(Request $request, CouponReduction $couponReduction, EntityManagerInterface $entityManager, SluggerInterface $slugger): Response
    {
        $form = $this->createForm(CouponReductionType::class, $couponReduction);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $logoFile = $form->get('logo')->getData();
            if ($logoFile) {
                $safeFilename = $slugger->slug(pathinfo($logoFile->getClientOriginalName(), PATHINFO_FILENAME));
                $newFilename = $safeFilename . '-' . uniqid() . '.' . $logoFile->guessExtension();
                $logoFile->move($this->getParameter('logos_directory'), $newFilename);
                $couponReduction->setLogo($newFilename);
            }
            $entityManager->flush();
            return $this->redirectToRoute('app_coupon_reduction_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('coupon_reduction/edit.html.twig', [
            'coupon_reduction' => $couponReduction,
            'form'             => $form,
        ]);
    }

    #[IsGranted('ROLE_ADMIN')]
    #[Route('/{id}', name: 'app_coupon_reduction_delete', methods: ['POST'], requirements: ['id' => '\d+'])]
    public function delete(Request $request, CouponReduction $couponReduction, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete' . $couponReduction->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($couponReduction);
            $entityManager->flush();
        }
        return $this->redirectToRoute('app_coupon_reduction_index', [], Response::HTTP_SEE_OTHER);
    }

    #[IsGranted('ROLE_ADMIN')]
    #[Route('/{id}/valider', name: 'app_coupon_reduction_valider_page', methods: ['GET'], requirements: ['id' => '\d+'])]
    public function validerPage(CouponReduction $couponReduction, UtilisateurCouponRepository $utilisateurCouponRepository): Response
    {
        $couponsEnAttente = $utilisateurCouponRepository->findBy([
            'couponReduction' => $couponReduction,
            'utilise'         => true,
        ]);

        return $this->render('coupon_reduction/valider.html.twig', [
            'coupon_reduction'    => $couponReduction,
            'coupons_en_attente' => $couponsEnAttente,
        ]);
    }

    #[IsGranted('ROLE_ADMIN')]
    #[Route('/{id}/utiliser/{utilisateurCouponId}', name: 'app_coupon_reduction_utiliser', methods: ['POST'], requirements: ['id' => '\d+'])]
    public function utiliser(
        CouponReduction $couponReduction,
        int $utilisateurCouponId,
        Request $request,
        EntityManagerInterface $entityManager,
        UtilisateurCouponRepository $utilisateurCouponRepository
    ): Response {
        $utilisateurCoupon = $utilisateurCouponRepository->find($utilisateurCouponId);

        if (!$utilisateurCoupon || $utilisateurCoupon->isUtilise()) {
            $this->addFlash('error', 'Ce coupon a déjà été utilisé ou est invalide.');
            return $this->redirectToRoute('app_coupon_reduction_valider_page', ['id' => $couponReduction->getId()]);
        }

        if ($this->isCsrfTokenValid('utiliser' . $utilisateurCoupon->getId(), $request->getPayload()->getString('_token'))) {
            $utilisateurCoupon->setUtilise(true);
            $utilisateurCoupon->setDateUtilisation(new \DateTimeImmutable());
            $entityManager->flush();
            $this->addFlash('success', 'Coupon validé avec succès !');
        }

        return $this->redirectToRoute('app_coupon_reduction_valider_page', ['id' => $couponReduction->getId()]);
    }

    #[IsGranted('ROLE_USER')]
    #[Route('/{id}/utiliser-mon-coupon', name: 'app_coupon_reduction_utiliser_mon_coupon', methods: ['POST'], requirements: ['id' => '\d+'])]
    public function utiliserMonCoupon(
        CouponReduction $couponReduction,
        Request $request,
        EntityManagerInterface $entityManager,
        UtilisateurCouponRepository $utilisateurCouponRepository
    ): Response {
        /** @var Utilisateur $user */
        $user = $this->getUser();

        $utilisateurCoupon = $utilisateurCouponRepository->findOneBy([
            'utilisateur'     => $user,
            'couponReduction' => $couponReduction,
        ]);

        if ($utilisateurCoupon && $utilisateurCoupon->isUtilise()) {
            $this->addFlash('error', 'Vous avez déjà utilisé ce coupon.');
            return $this->redirectToRoute('app_coupon_reduction_show', ['id' => $couponReduction->getId()]);
        }

        if ($this->isCsrfTokenValid('utiliser-mon-coupon' . $couponReduction->getId(), $request->getPayload()->getString('_token'))) {
            if (!$utilisateurCoupon) {
                $utilisateurCoupon = new UtilisateurCoupon();
                $utilisateurCoupon->setUtilisateur($user);
                $utilisateurCoupon->setCouponReduction($couponReduction);
                $entityManager->persist($utilisateurCoupon);
            }
            $utilisateurCoupon->setUtilise(true);
            $utilisateurCoupon->setDateUtilisation(new \DateTimeImmutable());
            $entityManager->flush();
            $this->addFlash('success', 'Succès, vous avez utilisé votre coupon ' . $couponReduction->getIntitule() . ' !');
        }

        return $this->redirectToRoute('app_coupon_reduction_show', ['id' => $couponReduction->getId()]);
    }
}
