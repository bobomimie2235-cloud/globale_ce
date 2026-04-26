<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;

class CookiesController extends AbstractController
{
    #[Route('/cookies/accept', name: 'app_cookies_accept', methods: ['POST'])]
    public function accept(Request $request): Response
    {
        $request->getSession()->set('cookies_accepted', true);
        return new Response('OK');
    }
}