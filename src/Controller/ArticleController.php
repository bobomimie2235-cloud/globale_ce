<?php

namespace App\Controller;

use App\Entity\Article;
use App\Form\ArticleType;
use App\Repository\ArticleRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Component\String\Slugger\SluggerInterface;

#[Route('/article')]
final class ArticleController extends AbstractController
{
    #[Route(name: 'app_article_index', methods: ['GET'])]
    public function index(ArticleRepository $articleRepository): Response
    {
        return $this->render('article/index.html.twig', [
            'articles' => $articleRepository->findAll(),
        ]);
    }

    #[IsGranted('ROLE_ADMIN')]
    #[Route('/new', name: 'app_article_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager, SluggerInterface $slugger): Response
    {
        $article = new Article();
        $form = $this->createForm(ArticleType::class, $article);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

          // ===== imgLogo =====
        $imgLogo = $form->get('imgLogo')->getData();
        if ($imgLogo) {
            $safeFilename = $slugger->slug(pathinfo($imgLogo->getClientOriginalName(), PATHINFO_FILENAME));
            $newFilename = $safeFilename . '-' . uniqid() . '.' . $imgLogo->guessExtension();
            $imgLogo->move($this->getParameter('articles_directory'), $newFilename);
            $article->setImgLogo($newFilename);
        }

        // ===== imgPhotosDevanture =====
        $imgDevanture = $form->get('imgPhotosDevanture')->getData();
        if ($imgDevanture) {
            $safeFilename = $slugger->slug(pathinfo($imgDevanture->getClientOriginalName(), PATHINFO_FILENAME));
            $newFilename = $safeFilename . '-' . uniqid() . '.' . $imgDevanture->guessExtension();
            $imgDevanture->move($this->getParameter('articles_directory'), $newFilename);
            $article->setImgPhotosDevanture($newFilename);
        }

        // ===== imgPhotosInterieur =====
        $imgInterieur = $form->get('imgPhotosInterieur')->getData();
        if ($imgInterieur) {
            $safeFilename = $slugger->slug(pathinfo($imgInterieur->getClientOriginalName(), PATHINFO_FILENAME));
            $newFilename = $safeFilename . '-' . uniqid() . '.' . $imgInterieur->guessExtension();
            $imgInterieur->move($this->getParameter('articles_directory'), $newFilename);
            $article->setImgPhotosInterieur($newFilename);
        }

            $entityManager->persist($article);
            $entityManager->flush();

            return $this->redirectToRoute('app_article_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('article/new.html.twig', [
            'article' => $article,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_article_show', methods: ['GET'])]
    public function show(Article $article): Response
    {
        return $this->render('article/show.html.twig', [
            'article' => $article,
        ]);
    }

    #[IsGranted('ROLE_ADMIN')]
    #[Route('/{id}/edit', name: 'app_article_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Article $article, EntityManagerInterface $entityManager, SluggerInterface $slugger): Response
    {
        $form = $this->createForm(ArticleType::class, $article);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

        // ===== imgLogo =====
        $imgLogo = $form->get('imgLogo')->getData();
        if ($imgLogo) {
            $safeFilename = $slugger->slug(pathinfo($imgLogo->getClientOriginalName(), PATHINFO_FILENAME));
            $newFilename = $safeFilename . '-' . uniqid() . '.' . $imgLogo->guessExtension();
            $imgLogo->move($this->getParameter('articles_directory'), $newFilename);
            $article->setImgLogo($newFilename);
        }

        // ===== imgPhotosDevanture =====
        $imgDevanture = $form->get('imgPhotosDevanture')->getData();
        if ($imgDevanture) {
            $safeFilename = $slugger->slug(pathinfo($imgDevanture->getClientOriginalName(), PATHINFO_FILENAME));
            $newFilename = $safeFilename . '-' . uniqid() . '.' . $imgDevanture->guessExtension();
            $imgDevanture->move($this->getParameter('articles_directory'), $newFilename);
            $article->setImgPhotosDevanture($newFilename);
        }

        // ===== imgPhotosInterieur =====
        $imgInterieur = $form->get('imgPhotosInterieur')->getData();
        if ($imgInterieur) {
            $safeFilename = $slugger->slug(pathinfo($imgInterieur->getClientOriginalName(), PATHINFO_FILENAME));
            $newFilename = $safeFilename . '-' . uniqid() . '.' . $imgInterieur->guessExtension();
            $imgInterieur->move($this->getParameter('articles_directory'), $newFilename);
            $article->setImgPhotosInterieur($newFilename);
        }

            $entityManager->flush();

            return $this->redirectToRoute('app_article_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('article/edit.html.twig', [
            'article' => $article,
            'form' => $form,
        ]);
    }

    #[IsGranted('ROLE_ADMIN')]
    #[Route('/{id}', name: 'app_article_delete', methods: ['POST'])]
    public function delete(Request $request, Article $article, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$article->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($article);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_article_index', [], Response::HTTP_SEE_OTHER);
    }
}
