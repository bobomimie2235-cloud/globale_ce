<?php

namespace App\Entity;

use App\Repository\CouponReductionRepository;
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
    #[Assert\NotBlank(message: "L’intitulé du coupon est obligatoire")]
    #[Assert\Length(
        min: 5,
        max: 100,
        minMessage: "L’intitulé doit contenir au moins {{ limit }} caractères",
        maxMessage: "L’intitulé ne doit pas dépasser {{ limit }} caractères"
    )]
    private ?string $intitule = null;

    #[ORM\Column(length: 500, nullable: true)]
    #[Assert\Length(
        min: 10,
        max: 500,
        minMessage: "La description doit contenir au moins {{ limit }} caractères",
        maxMessage: "La description ne doit pas dépasser {{ limit }} caractères"
    )]
    private ?string $description = null;

    #[ORM\Column]
    private ?bool $actif = null;

    #[ORM\OneToOne(cascade: ['persist', 'remove'])]
    private ?Article $article = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getReference(): ?string
    {
        return $this->reference;
    }

    public function setReference(string $reference): static
    {
        $this->reference = $reference;

        return $this;
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

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): static
    {
        $this->description = $description;

        return $this;
    }

    public function isActif(): ?bool
    {
        return $this->actif;
    }

    public function setActif(bool $actif): static
    {
        $this->actif = $actif;

        return $this;
    }

    public function getArticle(): ?Article
    {
        return $this->article;
    }

    public function setArticle(?Article $article): static
    {
        $this->article = $article;

        return $this;
    }
}
