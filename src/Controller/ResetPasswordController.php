<?php

namespace App\Controller;

use App\Entity\Utilisateur;
use App\Repository\UtilisateurRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class ResetPasswordController extends AbstractController
{
    // ===== Étape 1 — Formulaire demande email =====
    #[Route('/mot-de-passe-oublie', name: 'app_forgot_password', methods: ['GET', 'POST'])]
    public function forgotPassword(
        Request $request,
        UtilisateurRepository $utilisateurRepository,
        EntityManagerInterface $em,
        MailerInterface $mailer
    ): Response {
        $message = null;

        if ($request->isMethod('POST')) {
            $emailSaisi = $request->request->get('email');
            $utilisateur = $utilisateurRepository->findOneBy(['email' => $emailSaisi]);

            if ($utilisateur) {
                // ===== Génère un token unique =====
                $token = bin2hex(random_bytes(32));
                $utilisateur->setResetToken($token);
                $utilisateur->setResetTokenExpiry(new \DateTimeImmutable('+1 hour'));
                $em->flush();

                // ===== Envoie l'email =====
                $resetUrl = $this->generateUrl(
                    'app_reset_password',
                    ['token' => $token],
                    UrlGeneratorInterface::ABSOLUTE_URL
                );

                $email = (new Email())
                    ->from('noreply@globalece.fr')
                    ->to($utilisateur->getEmail())
                    ->subject('Réinitialisation de votre mot de passe')
                    ->html('
                        <p>Bonjour ' . $utilisateur->getPrenom() . ',</p>
                        <p>Cliquez sur le lien ci-dessous pour réinitialiser votre mot de passe :</p>
                        <p><a href="' . $resetUrl . '">' . $resetUrl . '</a></p>
                        <p>Ce lien expire dans 1 heure.</p>
                        <p>Si vous n\'avez pas demandé cette réinitialisation, ignorez cet email.</p>
                    ');

                $mailer->send($email);
            }

            // ===== Toujours afficher le même message (sécurité) =====
            $message = 'Si un compte existe avec cet email, vous recevrez un lien de réinitialisation.';
        }

        return $this->render('security/forgot_password.html.twig', [
            'message' => $message,
        ]);
    }

    // ===== Étape 2 — Formulaire nouveau mot de passe =====
    #[Route('/reinitialiser-mot-de-passe/{token}', name: 'app_reset_password', methods: ['GET', 'POST'])]
    public function resetPassword(
        string $token,
        Request $request,
        UtilisateurRepository $utilisateurRepository,
        EntityManagerInterface $em,
        UserPasswordHasherInterface $passwordHasher
    ): Response {
        $utilisateur = $utilisateurRepository->findOneBy(['resetToken' => $token]);

        // ===== Vérifie token valide et non expiré =====
        if (!$utilisateur || $utilisateur->getResetTokenExpiry() < new \DateTimeImmutable()) {
            $this->addFlash('error', 'Ce lien est invalide ou a expiré.');
            return $this->redirectToRoute('app_forgot_password');
        }

        if ($request->isMethod('POST')) {
            $newPassword = $request->request->get('password');
            $confirm = $request->request->get('confirm');

            if ($newPassword !== $confirm) {
                $this->addFlash('error', 'Les mots de passe ne correspondent pas.');
                return $this->redirectToRoute('app_reset_password', ['token' => $token]);
            }

            if (strlen($newPassword) < 6) {
                $this->addFlash('error', 'Le mot de passe doit contenir au moins 6 caractères.');
                return $this->redirectToRoute('app_reset_password', ['token' => $token]);
            }

            // ===== Hash et sauvegarde le nouveau mot de passe =====
            $hashedPassword = $passwordHasher->hashPassword($utilisateur, $newPassword);
            $utilisateur->setPassword($hashedPassword);
            $utilisateur->setResetToken(null);
            $utilisateur->setResetTokenExpiry(null);
            $em->flush();

            $this->addFlash('success', 'Mot de passe modifié avec succès. Vous pouvez vous connecter.');
            return $this->redirectToRoute('app_login');
        }

        return $this->render('security/reset_password.html.twig', [
            'token' => $token,
        ]);
    }
}