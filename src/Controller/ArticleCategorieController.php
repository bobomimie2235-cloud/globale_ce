<?php

namespace App\Controller;

use App\Entity\ArticleCategorie;
use App\Form\ArticleCategorieType;
use App\Repository\ArticleCategorieRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/article/categorie')]
final class ArticleCategorieController extends AbstractController
{
    #[Route(name: 'app_article_categorie_index', methods: ['GET'])]
    public function index(ArticleCategorieRepository $articleCategorieRepository): Response
    {
        return $this->render('article_categorie/index.html.twig', [
            'article_categories' => $articleCategorieRepository->findAll(),
        ]);
    }

    #[IsGranted('ROLE_ADMIN')]
    #[Route('/new', name: 'app_article_categorie_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $articleCategorie = new ArticleCategorie();
        $form = $this->createForm(ArticleCategorieType::class, $articleCategorie);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($articleCategorie);
            $entityManager->flush();

            return $this->redirectToRoute('app_article_categorie_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('article_categorie/new.html.twig', [
            'article_categorie' => $articleCategorie,
            'form' => $form,
        ]);
    }

    #[IsGranted('ROLE_ADMIN')]
    #[Route('/{id}', name: 'app_article_categorie_show', methods: ['GET'])]
    public function show(ArticleCategorie $articleCategorie): Response
    {
        return $this->render('article_categorie/show.html.twig', [
            'article_categorie' => $articleCategorie,
        ]);
    }

    #[IsGranted('ROLE_ADMIN')]
    #[Route('/{id}/edit', name: 'app_article_categorie_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, ArticleCategorie $articleCategorie, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(ArticleCategorieType::class, $articleCategorie);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_article_categorie_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('article_categorie/edit.html.twig', [
            'article_categorie' => $articleCategorie,
            'form' => $form,
        ]);
    }

    #[IsGranted('ROLE_ADMIN')]
    #[Route('/{id}', name: 'app_article_categorie_delete', methods: ['POST'])]
    public function delete(Request $request, ArticleCategorie $articleCategorie, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$articleCategorie->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($articleCategorie);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_article_categorie_index', [], Response::HTTP_SEE_OTHER);
    }
}
