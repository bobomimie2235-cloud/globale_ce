<?php

namespace App\Entity;

use App\Repository\ProduitCategorieRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: ProduitCategorieRepository::class)]
class ProduitCategorie
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
     * @var Collection<int, Produit>
     */
    #[ORM\OneToMany(targetEntity: Produit::class, mappedBy: 'produitCategorie')]
    private Collection $produits;

    public function __construct()
    {
        $this->produits = new ArrayCollection();
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
     * @return Collection<int, Produit>
     */
    public function getProduits(): Collection
    {
        return $this->produits;
    }

    public function addProduit(Produit $produit): static
    {
        if (!$this->produits->contains($produit)) {
            $this->produits->add($produit);
            $produit->setProduitCategorie($this);
        }

        return $this;
    }

    public function removeProduit(Produit $produit): static
    {
        if ($this->produits->removeElement($produit)) {
            // set the owning side to null (unless already changed)
            if ($produit->getProduitCategorie() === $this) {
                $produit->setProduitCategorie(null);
            }
        }

        return $this;
    }
}
