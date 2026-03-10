<?php

namespace App\Controller\Admin;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[IsGranted('ROLE_ADMIN')]
class DashboardController extends AbstractController
{
    #[Route('/admin', name: 'admin_dashboard')]
    public function index(): Response
    {
        $stats = [
            'commandes'             => 0,
            'commandes_total'       => 0,
            'utilisateurs'          => 0,
            'nouveaux_utilisateurs' => 0,
            'produits'              => 0,
            'categories'            => 0,
            'ca_mois'               => '0.00',
            'ca_total'              => '0.00',
        ];

        return $this->render('admin/dashboard/index.html.twig', [
            'stats'                 => $stats,
            'dernieres_commandes'   => [],
            'derniers_utilisateurs' => [],
        ]);
    }
}