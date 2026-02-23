<?php

namespace App\Controller;

use App\Entity\Utilisateur;
use App\Entity\UtilisateurAdresse;
use App\Form\UtilisateurAdresseType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/profil')]
class ProfilController extends AbstractController
{
    #[Route('/', name: 'app_profil')]
    public function index(Request $request, EntityManagerInterface $em): Response
    {
        // Utilisateur connecté
        $user = $this->getUser();

        // Vérifie que l’utilisateur est connecté
        if (!$user instanceof Utilisateur) {
            throw $this->createAccessDeniedException('Vous devez être connecté pour accéder à votre profil.');
        }

        // Formulaire pour ajouter une adresse
        $adresse = new UtilisateurAdresse();
        $form = $this->createForm(UtilisateurAdresseType::class, $adresse);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Associer automatiquement l’adresse à l’utilisateur connecté
            $adresse->setUtilisateur($this->getUser());
            $em->persist($adresse);
            $em->flush();

            $this->addFlash('success', 'Adresse ajoutée avec succès');
            return $this->redirectToRoute('app_profil');
        }

        return $this->render('profil/index.html.twig', [
            'user' => $user,
            'formAdresse' => $form->createView(),
            'adresses' => $user->getUtilisateurAdresses(),
            // 'commandes' => $user->getCommandes(),
            // 'coupons' => $user->getCoupons(),
        ]);
    }
}