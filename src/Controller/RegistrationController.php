<?php

namespace App\Controller;


use App\Entity\Utilisateur;
use \DateTimeImmutable;
use App\Form\RegistrationFormType;
use App\Repository\UtilisateurGroupeRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\FormError;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Attribute\Route;

class RegistrationController extends AbstractController
{
    #[Route('/register', name: 'app_register')]
    public function register(
        Request $request,
        UserPasswordHasherInterface $userPasswordHasher,
        EntityManagerInterface $entityManager,
        UtilisateurGroupeRepository $utilisateurGroupeRepository
    ): Response {
        $user = new Utilisateur();

        $form = $this->createForm(RegistrationFormType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            // ✅ Date d'inscription automatique
            $user->setDateInscription(new \DateTimeImmutable());

            // 🔐 Récupération et normalisation du code groupe
            $codeGroupe = strtoupper(trim(
                $form->get('referenceGroupe')->getData()
            ));

            // 🔍 Recherche du groupe en base
            $groupe = $utilisateurGroupeRepository->findOneBy([
                'referenceGroupe' => $codeGroupe,
            ]);

            // ❌ Code invalide
            if (!$groupe) {
                $form->get('referenceGroupe')
                    ->addError(new FormError('Code de groupe invalide'));
            } else {
                // ✅ Association du groupe à l'utilisateur
                $user->setUtilisateurGroupe($groupe);

                // 🔑 Encodage du mot de passe
                $plainPassword = $form->get('plainPassword')->getData();
                $user->setPassword(
                    $userPasswordHasher->hashPassword($user, $plainPassword)
                );

                // ✅ RÔLE PAR DÉFAUT (OBLIGATOIRE)
                $user->setRoles(['ROLE_USER']);

                // 💾 Sauvegarde
                $entityManager->persist($user);
                $entityManager->flush();

                // 🔁 Redirection après inscription
                return $this->redirectToRoute('app_accueil');
            }
        }

        return $this->render('registration/register.html.twig', [
            'registrationForm' => $form,
        ]);
    }
}
