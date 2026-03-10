<?php

namespace App\Controller\Admin;

use App\Entity\Article;
use App\Form\ArticleType;
use App\Repository\ArticleRepository;
use App\Repository\ArticleCategorieRepository;
use App\Repository\DepartementRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Component\String\Slugger\SluggerInterface;

#[Route('/admin/articles', name: 'admin_article_')]
#[IsGranted('ROLE_ADMIN')]
class ArticleController extends AbstractController
{
    // ===== LISTING =====
    #[Route('', name: 'index', methods: ['GET'])]
    public function index(
        ArticleRepository $articleRepository,
        ArticleCategorieRepository $articleCategorieRepository,
        DepartementRepository $departementRepository,
        Request $request
    ): Response {
        $search      = trim((string) $request->query->get('search', '')) ?: null;
        $categorieId = $request->query->get('categorie', '') ?: null;
        $deptId      = $request->query->get('departement', '') ?: null;

        $articles = $articleRepository->findByFiltresAdmin($search, $categorieId ? (int)$categorieId : null, $deptId ? (int)$deptId : null);

        return $this->render('admin/article/index.html.twig', [
            'articles'       => $articles,
            'categories'     => $articleCategorieRepository->findAll(),
            'departements'   => $departementRepository->findAll(),
            'search'         => $search,
            'categorieActif' => $categorieId,
            'deptActif'      => $deptId,
        ]);
    }

    // ===== VOIR UN ARTICLE =====
    #[Route('/{id}', name: 'show', methods: ['GET'], requirements: ['id' => '\d+'])]
    public function show(Article $article): Response
    {
        return $this->render('admin/article/show.html.twig', [
            'article' => $article,
        ]);
    }

    // ===== NOUVEAU =====
    #[Route('/new', name: 'new', methods: ['GET', 'POST'])]
    public function new(
        Request $request,
        EntityManagerInterface $entityManager,
        SluggerInterface $slugger
    ): Response {
        $article = new Article();
        $form    = $this->createForm(ArticleType::class, $article);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->handleImages($form, $article, $slugger);
            $entityManager->persist($article);
            $entityManager->flush();
            $this->addFlash('success', 'Article créé avec succès.');
            return $this->redirectToRoute('admin_article_index');
        }

        return $this->render('admin/article/new.html.twig', [
            'article' => $article,
            'form'    => $form,
        ]);
    }

    // ===== MODIFIER =====
    #[Route('/{id}/edit', name: 'edit', methods: ['GET', 'POST'], requirements: ['id' => '\d+'])]
    public function edit(
        Request $request,
        Article $article,
        EntityManagerInterface $entityManager,
        SluggerInterface $slugger
    ): Response {
        $form = $this->createForm(ArticleType::class, $article);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->handleImages($form, $article, $slugger);
            $entityManager->flush();
            $this->addFlash('success', 'Article modifié avec succès.');
            return $this->redirectToRoute('admin_article_show', ['id' => $article->getId()]);
        }

        return $this->render('admin/article/edit.html.twig', [
            'article' => $article,
            'form'    => $form,
        ]);
    }

    // ===== SUPPRIMER =====
    #[Route('/{id}/delete', name: 'delete', methods: ['POST'], requirements: ['id' => '\d+'])]
    public function delete(
        Request $request,
        Article $article,
        EntityManagerInterface $entityManager
    ): Response {
        if ($this->isCsrfTokenValid('delete' . $article->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($article);
            $entityManager->flush();
            $this->addFlash('success', 'Article supprimé.');
        }
        return $this->redirectToRoute('admin_article_index');
    }

    // ===== HELPER : gestion uploads images =====
    private function handleImages($form, Article $article, SluggerInterface $slugger): void
    {
        foreach ([
            'imgLogo'            => 'setImgLogo',
            'imgPhotosDevanture' => 'setImgPhotosDevanture',
            'imgPhotosInterieur' => 'setImgPhotosInterieur',
        ] as $field => $setter) {
            $file = $form->get($field)->getData();
            if ($file) {
                $safeName    = $slugger->slug(pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME));
                $newFilename = $safeName . '-' . uniqid() . '.' . $file->guessExtension();
                $file->move($this->getParameter('articles_directory'), $newFilename);
                $article->$setter($newFilename);
            }
        }
    }
}
