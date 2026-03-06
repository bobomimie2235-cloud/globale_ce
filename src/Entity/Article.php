<?php

namespace App\Entity;

use Symfony\Component\Validator\Constraints as Assert;
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
    #[Assert\NotBlank(message: 'Le titre est obligatoire')]
    #[Assert\Length(
        min: 3,
        max: 150,
        minMessage: 'Le titre doit contenir au moins {{ limit }} caractères',
        maxMessage: 'Le titre ne doit pas dépasser {{ limit }} caractères'
    )]
    private ?string $titre = null;

    #[ORM\Column(type: 'text', nullable: true)]
    #[Assert\Length(
        min: 5,
        max: 500,
        minMessage: 'La description doit contenir au moins {{ limit }} caractères',
        maxMessage: 'La description ne doit pas dépasser {{ limit }} caractères'
    )]
    private ?string $description = null;

    #[ORM\Column(type: 'text', nullable: true)]
    #[Assert\Length(
        min: 5,
        max: 500,
        minMessage: 'La description doit contenir au moins {{ limit }} caractères',
        maxMessage: 'La description ne doit pas dépasser {{ limit }} caractères'
    )]
    private ?string $infosActivite = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $siteWeb = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $imgLogo = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $imgPhotosDevanture = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $imgPhotosInterieur = null;

    #[ORM\Column(type: 'text', nullable: true)]
    #[Assert\Length(
        min: 3,
        max: 500,
        minMessage: "L'Offre Commerciale doit contenir au moins {{ limit }} caractères",
        maxMessage: "L'Offre Commerciale ne doit pas dépasser {{ limit }} caractères"
    )]
    private ?string $offreCommerciale = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $horaires = null;

    #[ORM\ManyToOne(inversedBy: 'articles')]
    private ?Utilisateur $utilisateur = null;

    #[ORM\ManyToOne(inversedBy: 'articles')]
    private ?ArticleCategorie $articleCategorie = null;

    #[ORM\OneToOne(cascade: ['persist', 'remove'])]
    private ?CouponReduction $couponReduction = null;

    #[ORM\Column(length: 50, nullable: true)]
    private ?string $offre = null;

        // ===== NOUVELLE RELATION DEPARTEMENT =====
    #[ORM\ManyToOne(inversedBy: 'articles')]
    #[ORM\JoinColumn(nullable: true)]
    private ?Departement $departement = null;

    // =========================================

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

    public function getOffre(): ?string
{
    return $this->offre;
}

public function setOffre(?string $offre): static
{
    $this->offre = $offre;
    return $this;
}

    public function getDepartement(): ?Departement
    {
        return $this->departement;
    }

    public function setDepartement(?Departement $departement): static
    {
        $this->departement = $departement;
        return $this;
    }
}
