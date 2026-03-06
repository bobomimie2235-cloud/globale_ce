<?php

namespace App\Entity;

use App\Repository\DepartementRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: DepartementRepository::class)]
class Departement
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 3)]
    private ?string $numero = null;

    #[ORM\Column(length: 100)]
    private ?string $nom = null;

    #[ORM\OneToMany(mappedBy: 'departement', targetEntity: Article::class)]
    private Collection $articles;

    #[ORM\OneToMany(mappedBy: 'departement', targetEntity: CouponReduction::class)]
    private Collection $couponReductions;

    #[ORM\OneToMany(mappedBy: 'departement', targetEntity: Produit::class)]
    private Collection $produits;

    public function __construct()
    {
        $this->articles         = new ArrayCollection();
        $this->couponReductions = new ArrayCollection();
        $this->produits         = new ArrayCollection();
    }

    public function getId(): ?int { return $this->id; }

    public function getNumero(): ?string { return $this->numero; }
    public function setNumero(string $numero): static { $this->numero = $numero; return $this; }

    public function getNom(): ?string { return $this->nom; }
    public function setNom(string $nom): static { $this->nom = $nom; return $this; }

    public function __toString(): string { return $this->numero . ' - ' . $this->nom; }

    /** @return Collection<int, Article> */
    public function getArticles(): Collection { return $this->articles; }

    public function addArticle(Article $article): static
    {
        if (!$this->articles->contains($article)) {
            $this->articles->add($article);
            $article->setDepartement($this);
        }
        return $this;
    }

    public function removeArticle(Article $article): static
    {
        if ($this->articles->removeElement($article)) {
            if ($article->getDepartement() === $this) {
                $article->setDepartement(null);
            }
        }
        return $this;
    }

    /** @return Collection<int, CouponReduction> */
    public function getCouponReductions(): Collection { return $this->couponReductions; }

    public function addCouponReduction(CouponReduction $couponReduction): static
    {
        if (!$this->couponReductions->contains($couponReduction)) {
            $this->couponReductions->add($couponReduction);
            $couponReduction->setDepartement($this);
        }
        return $this;
    }

    public function removeCouponReduction(CouponReduction $couponReduction): static
    {
        if ($this->couponReductions->removeElement($couponReduction)) {
            if ($couponReduction->getDepartement() === $this) {
                $couponReduction->setDepartement(null);
            }
        }
        return $this;
    }

    /** @return Collection<int, Produit> */
    public function getProduits(): Collection { return $this->produits; }

    public function addProduit(Produit $produit): static
    {
        if (!$this->produits->contains($produit)) {
            $this->produits->add($produit);
            $produit->setDepartement($this);
        }
        return $this;
    }

    public function removeProduit(Produit $produit): static
    {
        if ($this->produits->removeElement($produit)) {
            if ($produit->getDepartement() === $this) {
                $produit->setDepartement(null);
            }
        }
        return $this;
    }
}
