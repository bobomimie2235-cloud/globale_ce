<?php

namespace App\Controller;

use App\Entity\Utilisateur;
use App\Entity\UtilisateurAdresse;
use App\Form\UtilisateurAdresseType;
use App\Repository\CommandeRepository;
use App\Repository\UtilisateurCouponRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/profil')]
class ProfilController extends AbstractController
{
    #[Route('/', name: 'app_profil')]
    public function index(
        Request $request,
        EntityManagerInterface $em,
        UtilisateurCouponRepository $utilisateurCouponRepository,
        CommandeRepository $commandeRepository
    ): Response {
        /** @var Utilisateur $user */
        $user = $this->getUser();

        if (!$user instanceof Utilisateur) {
            throw $this->createAccessDeniedException('Vous devez être connecté pour accéder à votre profil.');
        }

        // ===== Formulaire adresse =====
        $adresse = new UtilisateurAdresse();
        $form = $this->createForm(UtilisateurAdresseType::class, $adresse);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $adresse->setUtilisateur($user);
            $em->persist($adresse);
            $em->flush();

            $this->addFlash('success', 'Adresse ajoutée avec succès');
            return $this->redirectToRoute('app_profil');
        }

        // ===== Coupons utilisés =====
        $utilisateurCoupons = $utilisateurCouponRepository->findBy([
            'utilisateur' => $user,
            'utilise' => true,
        ]);

        // ===== Commandes validées =====
        $commandes = $commandeRepository->findBy(
            ['utilisateur' => $user, 'statut' => 'validee'],
            ['dateCommande' => 'DESC']  // les plus récentes en premier
        );

        return $this->render('profil/index.html.twig', [
            'user' => $user,
            'formAdresse' => $form->createView(),
            'adresses' => $user->getUtilisateurAdresses(),
            'commandes' => $commandes,
            'coupons' => $utilisateurCoupons,
        ]);
    }
}