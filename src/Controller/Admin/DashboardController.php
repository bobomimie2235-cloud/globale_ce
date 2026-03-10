<?php

namespace App\Controller\Admin;

use App\Repository\ArticleRepository;
use App\Repository\CommandeRepository;
use App\Repository\CouponReductionRepository;
use App\Repository\ProduitCategorieRepository;
use App\Repository\ProduitRepository;
use App\Repository\UtilisateurGroupeRepository;
use App\Repository\UtilisateurRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[IsGranted('ROLE_ADMIN')]
class DashboardController extends AbstractController
{
    #[Route('/admin', name: 'admin_dashboard')]
    public function index(
        CommandeRepository          $commandeRepository,
        UtilisateurRepository       $utilisateurRepository,
        UtilisateurGroupeRepository $utilisateurGroupeRepository,
        ProduitRepository           $produitRepository,
        ProduitCategorieRepository  $produitCategorieRepository,
        ArticleRepository           $articleRepository,
        CouponReductionRepository   $couponReductionRepository,
    ): Response {

        $stats = [
            // Commandes
            'commandes'             => $commandeRepository->countThisMonth(),
            'commandes_total'       => $commandeRepository->count([]),

            // Utilisateurs
            'utilisateurs'          => $utilisateurRepository->count([]),
            'nouveaux_utilisateurs' => $utilisateurRepository->countThisMonth(),

            // Produits
            'produits'              => $produitRepository->countAll(),
            'categories'            => $produitCategorieRepository->countAll(),

            // Articles
            'articles'              => $articleRepository->countAll(),

            // Coupons
            'coupons_total'         => $couponReductionRepository->countAll(),
            'coupons_actifs'        => $couponReductionRepository->countActifs(),
            'coupons_inactifs'      => $couponReductionRepository->countInactifs(),

            // Chiffre d'affaires
            'ca_mois'               => $commandeRepository->caThisMonth(),
            'ca_total'              => $commandeRepository->caTotal(),
        ];

        return $this->render('admin/dashboard/index.html.twig', [
            'stats'                 => $stats,
            'dernieres_commandes'   => $commandeRepository->findDernieres(5),
            'derniers_utilisateurs' => $utilisateurRepository->findDerniers(5),
            'derniers_articles'     => $articleRepository->findDerniers(5),
            'groupes_stats'         => $utilisateurGroupeRepository->findGroupesAvecStats(),
        ]);
    }
}
