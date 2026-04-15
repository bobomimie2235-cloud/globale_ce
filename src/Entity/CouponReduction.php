<?php

namespace App\Entity;

use App\Repository\CouponReductionRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

#[ORM\Entity(repositoryClass: CouponReductionRepository::class)]
#[UniqueEntity(
    fields: ['reference'],
    message: 'Cette référence est déjà utilisée.'
)]
class CouponReduction
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message: "La référence du coupon est obligatoire")]
    #[Assert\Length(
        min: 6,
        max: 50,
        minMessage: "La référence doit contenir au moins {{ limit }} caractères",
        maxMessage: "La référence ne doit pas dépasser {{ limit }} caractères"
    )]
    private ?string $reference = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message: "L'intitulé du coupon est obligatoire")]
    #[Assert\Length(
        min: 5,
        max: 100,
        minMessage: "L'intitulé doit contenir au moins {{ limit }} caractères",
        maxMessage: "L'intitulé ne doit pas dépasser {{ limit }} caractères"
    )]
    private ?string $intitule = null;

    #[ORM\Column]
    private ?bool $actif = false;

    #[ORM\OneToOne(cascade: ['persist', 'remove'])]
    private ?Article $article = null;

    #[ORM\Column(length: 255)]
    private ?string $adresse = null;

    #[ORM\Column(length: 255)]
    private ?string $ville = null;

    #[ORM\Column(length: 255)]
    private ?string $offreCommerciale = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $logo = null;

    #[ORM\ManyToOne(inversedBy: 'couponReductions')]
    private ?CouponCategorie $couponCategorie = null;

    /**
     * @var Collection<int, UtilisateurCoupon>
     */
    #[ORM\OneToMany(targetEntity: UtilisateurCoupon::class, mappedBy: 'couponReduction')]
    private Collection $utilisateurCoupons;

    #[ORM\Column(length: 50, nullable: true)]
    private ?string $offre = null;

    // ===== NOUVELLE RELATION DEPARTEMENT =====
    #[ORM\ManyToOne(inversedBy: 'couponReductions')]
    #[ORM\JoinColumn(nullable: true)]
    private ?Departement $departement = null;
    // =========================================

    public function __construct()
    {
        $this->utilisateurCoupons = new ArrayCollection();
    }

    public function getId(): ?int { return $this->id; }

    public function getReference(): ?string { return $this->reference; }
    public function setReference(string $reference): static { $this->reference = $reference; return $this; }

    public function getIntitule(): ?string { return $this->intitule; }
    public function setIntitule(string $intitule): static { $this->intitule = $intitule; return $this; }

    public function isActif(): ?bool { return $this->actif; }
    public function setActif(bool $actif): static { $this->actif = $actif ?? false; return $this; }

    public function getArticle(): ?Article { return $this->article; }
    public function setArticle(?Article $article): static { $this->article = $article; return $this; }

    public function getAdresse(): ?string { return $this->adresse; }
    public function setAdresse(string $adresse): static { $this->adresse = $adresse; return $this; }

    public function getVille(): ?string { return $this->ville; }
    public function setVille(string $ville): static { $this->ville = $ville; return $this; }

    public function getOffreCommerciale(): ?string { return $this->offreCommerciale; }
    public function setOffreCommerciale(string $offreCommerciale): static { $this->offreCommerciale = $offreCommerciale; return $this; }

    public function getLogo(): ?string { return $this->logo; }
    public function setLogo(string $logo): static { $this->logo = $logo; return $this; }

    public function getCouponCategorie(): ?CouponCategorie { return $this->couponCategorie; }
    public function setCouponCategorie(?CouponCategorie $couponCategorie): static { $this->couponCategorie = $couponCategorie; return $this; }

    public function getOffre(): ?string { return $this->offre; }
    public function setOffre(?string $offre): static { $this->offre = $offre; return $this; }

    public function getDepartement(): ?Departement { return $this->departement; }
    public function setDepartement(?Departement $departement): static { $this->departement = $departement; return $this; }

    /** @return Collection<int, UtilisateurCoupon> */
    public function getUtilisateurCoupons(): Collection { return $this->utilisateurCoupons; }

    public function addUtilisateurCoupon(UtilisateurCoupon $utilisateurCoupon): static
    {
        if (!$this->utilisateurCoupons->contains($utilisateurCoupon)) {
            $this->utilisateurCoupons->add($utilisateurCoupon);
            $utilisateurCoupon->setCouponReduction($this);
        }
        return $this;
    }

    public function removeUtilisateurCoupon(UtilisateurCoupon $utilisateurCoupon): static
    {
        if ($this->utilisateurCoupons->removeElement($utilisateurCoupon)) {
            if ($utilisateurCoupon->getCouponReduction() === $this) {
                $utilisateurCoupon->setCouponReduction(null);
            }
        }
        return $this;
    }
}
