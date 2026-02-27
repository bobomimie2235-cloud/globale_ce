<?php

namespace App\Entity;

use App\Repository\UtilisateurCouponRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: UtilisateurCouponRepository::class)]
class UtilisateurCoupon
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    private ?bool $utilise = false;

    #[ORM\Column(nullable: true)]
    private ?\DateTimeImmutable $dateUtilisation = null;

    #[ORM\ManyToOne(targetEntity: Utilisateur::class)]
    #[ORM\JoinColumn(nullable: false)]
    private ?Utilisateur $utilisateur = null;

    #[ORM\ManyToOne(targetEntity: CouponReduction::class)]
    #[ORM\JoinColumn(nullable: false)]
    private ?CouponReduction $couponReduction = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function isUtilise(): bool
    {
        return $this->utilise;
    }
    public function setUtilise(bool $utilise): static
    {
        $this->utilise = $utilise;
        return $this;
    }

    public function getDateUtilisation(): ?\DateTimeImmutable
    {
        return $this->dateUtilisation;
    }
    public function setDateUtilisation(?\DateTimeImmutable $dateUtilisation): static
    {
        $this->dateUtilisation = $dateUtilisation;
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

    public function getUtilisateur(): ?Utilisateur
    {
        return $this->utilisateur;
    }
    public function setUtilisateur(?Utilisateur $utilisateur): static
    {
        $this->utilisateur = $utilisateur;
        return $this;
    }
}
