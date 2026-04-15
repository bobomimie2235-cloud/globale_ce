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
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\HttpFoundation\RequestStack;

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

    #[Route('/supprimer-compte', name: 'app_profil_supprimer', methods: ['POST'])]
    #[IsGranted('ROLE_USER')]
    public function supprimerCompte(
        Request $request,
        EntityManagerInterface $em,
        UserPasswordHasherInterface $passwordHasher,
        TokenStorageInterface $tokenStorage,
        RequestStack $requestStack
    ): Response {
        /** @var Utilisateur $user */
        $user = $this->getUser();

        if (!$user instanceof Utilisateur) {
            throw $this->createAccessDeniedException();
        }

        // Vérification du token CSRF
        if (!$this->isCsrfTokenValid('supprimer_compte_' . $user->getId(), $request->request->get('_token'))) {
            $this->addFlash('error', 'Token de sécurité invalide. Veuillez réessayer.');
            return $this->redirectToRoute('app_profil');
        }

        // Vérification du mot de passe
        $motDePasse = $request->request->get('mot_de_passe');
        if (!$motDePasse || !$passwordHasher->isPasswordValid($user, $motDePasse)) {
            $this->addFlash('error', 'Mot de passe incorrect. La suppression a été annulée.');
            return $this->redirectToRoute('app_profil');
        }

        // Déconnexion avant suppression
        $tokenStorage->setToken(null);
        $session = $requestStack->getSession();
        $session->invalidate();

        // Suppression du compte
        $em->remove($user);
        $em->flush();

        $this->addFlash('success', 'Votre compte a été supprimé. À bientôt !');
        return $this->redirectToRoute('app_accueil');
    }
}