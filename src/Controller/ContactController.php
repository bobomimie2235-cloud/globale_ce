<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Symfony\Component\Routing\Attribute\Route;

class ContactController extends AbstractController
{
    #[Route('/contact', name: 'app_contact', methods: ['POST'])]
    public function contact(Request $request, MailerInterface $mailer): Response
    {
        $nom        = $request->request->get('nom');
        $fonction   = $request->request->get('fonction');
        $groupe     = $request->request->get('groupe');
        $ville      = $request->request->get('ville');
        $telephone  = $request->request->get('telephone');
        $emailUser  = $request->request->get('email');
        $message    = $request->request->get('message');

        $email = (new Email())
            ->from('noreply@globalece.fr')
            ->to('globale-ce@orange.fr')
            ->replyTo($emailUser)
            ->subject('Nouveau contact : ' . $nom)
            ->html('
                <h2>Nouveau message de contact</h2>
                <p><strong>Nom, Prénom :</strong> ' . $nom . '</p>
                <p><strong>Fonction :</strong> ' . $fonction . '</p>
                <p><strong>Nom du Groupe :</strong> ' . $groupe . '</p>
                <p><strong>Ville :</strong> ' . $ville . '</p>
                <p><strong>Téléphone :</strong> ' . $telephone . '</p>
                <p><strong>Email :</strong> ' . $emailUser . '</p>
                <p><strong>Message :</strong><br>' . nl2br($message) . '</p>
            ');

        $mailer->send($email);

        $this->addFlash('success', 'Votre message a bien été envoyé. Un conseiller vous répondra sous 24h ouvrées.');
        return $this->redirectToRoute('app_accueil', ['#' => 'contact']);
    }

    // ROUTE PAGE CONTACT - FORMULAIRE
    #[Route('/contactez-nous', name: 'app_contactez_nous', methods: ['GET'])]
    public function contactezNous(): Response
    {
        return $this->render('contact/index.html.twig');
    }
}
