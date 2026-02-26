<?php

namespace App\Entity;

use App\Repository\ArticleCategorieRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: ArticleCategorieRepository::class)]
class ArticleCategorie
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message: "L'Intitulé est obligatoire")]
    #[Assert\Length(
        min: 3,
        max: 100,
        minMessage: "L'Intitulé doit contenir au moins {{ limit }} caractères",
        maxMessage: "L'Intitulé ne doit pas dépasser {{ limit }} caractères"
    )]
    private ?string $intitule = null;

    /**
     * @var Collection<int, Article>
     */
    #[ORM\OneToMany(targetEntity: Article::class, mappedBy: 'articleCategorie')]
    private Collection $articles;

    public function __construct()
    {
        $this->articles = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getIntitule(): ?string
    {
        return $this->intitule;
    }

    public function setIntitule(string $intitule): static
    {
        $this->intitule = $intitule;

        return $this;
    }

    /**
     * @return Collection<int, Article>
     */
    public function getArticles(): Collection
    {
        return $this->articles;
    }

    public function addArticle(Article $article): static
    {
        if (!$this->articles->contains($article)) {
            $this->articles->add($article);
            $article->setArticleCategorie($this);
        }

        return $this;
    }

    public function removeArticle(Article $article): static
    {
        if ($this->articles->removeElement($article)) {
            // set the owning side to null (unless already changed)
            if ($article->getArticleCategorie() === $this) {
                $article->setArticleCategorie(null);
            }
        }

        return $this;
    }
}
