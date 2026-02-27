<?php

namespace App\Controller;

use App\Repository\ArticleRepository;
use App\Repository\ProduitRepository;
use App\Repository\CouponReductionRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class SearchController extends AbstractController
{
    #[Route('/recherche', name: 'app_recherche', methods: ['GET'])]
    public function recherche(
        Request $request,
        ArticleRepository $articleRepository,
        ProduitRepository $produitRepository,
        CouponReductionRepository $couponReductionRepository
    ): Response {
        $q = trim($request->query->get('q', ''));
        $filtre = $request->query->get('filtre', 'tout');

        $articles = [];
        $produits = [];
        $coupons = [];

        if ($q !== '') {
            if ($filtre === 'tout' || $filtre === 'articles') {
                $articles = $articleRepository->search($q);
            }
            if ($filtre === 'tout' || $filtre === 'produits') {
                $produits = $produitRepository->search($q);
            }
            if ($filtre === 'tout' || $filtre === 'coupons') {
                $coupons = $couponReductionRepository->search($q);
            }
        }

        return $this->render('search/resultats.html.twig', [
            'q' => $q,
            'filtre' => $filtre,
            'articles' => $articles,
            'produits' => $produits,
            'coupons' => $coupons,
        ]);
    }
}