<?php

namespace App\Entity;

use App\Repository\ArticleRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ArticleRepository::class)]
class Article
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $titre = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $description = null;

    #[ORM\Column(length: 500, nullable: true)]
    private ?string $infosActivite = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $siteWeb = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $imgLogo = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $imgPhotosDevanture = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $imgPhotosInterieur = null;

    #[ORM\Column(length: 500, nullable: true)]
    private ?string $offreCommerciale = null;

    #[ORM\Column(length: 500, nullable: true)]
    private ?string $horaires = null;

    #[ORM\ManyToOne(inversedBy: 'articles')]
    private ?Utilisateur $utilisateur = null;

    #[ORM\ManyToOne(inversedBy: 'articles')]
    private ?ArticleCategorie $articleCategorie = null;

    #[ORM\OneToOne(cascade: ['persist', 'remove'])]
    private ?CouponReduction $couponReduction = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitre(): ?string
    {
        return $this->titre;
    }

    public function setTitre(string $titre): static
    {
        $this->titre = $titre;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): static
    {
        $this->description = $description;

        return $this;
    }

    public function getInfosActivite(): ?string
    {
        return $this->infosActivite;
    }

    public function setInfosActivite(?string $infosActivite): static
    {
        $this->infosActivite = $infosActivite;

        return $this;
    }

    public function getSiteWeb(): ?string
    {
        return $this->siteWeb;
    }

    public function setSiteWeb(?string $siteWeb): static
    {
        $this->siteWeb = $siteWeb;

        return $this;
    }

    public function getImgLogo(): ?string
    {
        return $this->imgLogo;
    }

    public function setImgLogo(?string $imgLogo): static
    {
        $this->imgLogo = $imgLogo;

        return $this;
    }

    public function getImgPhotosDevanture(): ?string
    {
        return $this->imgPhotosDevanture;
    }

    public function setImgPhotosDevanture(?string $imgPhotosDevanture): static
    {
        $this->imgPhotosDevanture = $imgPhotosDevanture;

        return $this;
    }

    public function getImgPhotosInterieur(): ?string
    {
        return $this->imgPhotosInterieur;
    }

    public function setImgPhotosInterieur(?string $imgPhotosInterieur): static
    {
        $this->imgPhotosInterieur = $imgPhotosInterieur;

        return $this;
    }

    public function getOffreCommerciale(): ?string
    {
        return $this->offreCommerciale;
    }

    public function setOffreCommerciale(?string $offreCommerciale): static
    {
        $this->offreCommerciale = $offreCommerciale;

        return $this;
    }

    public function getHoraires(): ?string
    {
        return $this->horaires;
    }

    public function setHoraires(?string $horaires): static
    {
        $this->horaires = $horaires;

        return $this;
    }

    public function getUtilisateur(): ?Utilisateur
    {
        return $this->utilisateur;
    }

    public function setUtilisateur(?Utilisateur $utilisateur): static
    {
        $this->utilisateur = $utilisateur;

        return $this;
    }

    public function getArticleCategorie(): ?ArticleCategorie
    {
        return $this->articleCategorie;
    }

    public function setArticleCategorie(?ArticleCategorie $articleCategorie): static
    {
        $this->articleCategorie = $articleCategorie;

        return $this;
    }

    public function getCouponReduction(): ?CouponReduction
    {
        return $this->couponReduction;
    }

    public function setCouponReduction(?CouponReduction $couponReduction): static
    {
        $this->couponReduction = $couponReduction;

        return $this;
    }
}
