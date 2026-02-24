<?php

namespace App\Entity;

use App\Repository\ProduitRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

#[ORM\Entity(repositoryClass: ProduitRepository::class)]
#[UniqueEntity(
    fields: ['reference'],
    message: 'Cette référence est déjà utilisée.'
)]
class Produit
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message: "La référence du produit est obligatoire")]
    #[Assert\Length(
        min: 3,
        max: 50,
        minMessage: "La référence doit contenir au moins {{ limit }} caractères",
        maxMessage: "La référence ne doit pas dépasser {{ limit }} caractères"
    )]
    private ?string $reference = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message: "L’intitulé du produit est obligatoire")]
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

    #[ORM\Column(type: Types::DECIMAL, precision: 10, scale: 2, nullable: true)]
    #[Assert\Type(
        type: 'numeric',
        message: 'Le prix doit être un nombre'
    )]
    #[Assert\PositiveOrZero(message: 'Le prix doit être supérieur ou égal à 0')]
    private ?string $prixUnitaire = null;

    #[ORM\Column]
    #[Assert\NotNull(message: 'Le stock est obligatoire')]
    #[Assert\Type(
        type: 'integer',
        message: 'Le stock doit être un nombre entier'
    )]
    #[Assert\PositiveOrZero(message: 'Le stock doit être supérieur ou égal à 0')]
    private ?int $stock = null;

    #[ORM\Column]
    private ?\DateTime $dateCreation = null;

    /**
     * @var Collection<int, CommandeProduit>
     */
    #[ORM\OneToMany(targetEntity: CommandeProduit::class, mappedBy: 'produit')]
    private Collection $commandeProduits;

    #[ORM\ManyToOne(inversedBy: 'produits')]
    private ?ProduitCategorie $produitCategorie = null;

    public function __construct()
    {
        $this->commandeProduits = new ArrayCollection();
    }

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

    public function getPrixUnitaire(): ?string
    {
        return $this->prixUnitaire;
    }

    public function setPrixUnitaire(?string $prixUnitaire): static
    {
        $this->prixUnitaire = $prixUnitaire;

        return $this;
    }

    public function getStock(): ?int
    {
        return $this->stock;
    }

    public function setStock(int $stock): static
    {
        $this->stock = $stock;

        return $this;
    }

    public function getDateCreation(): ?\DateTime
    {
        return $this->dateCreation;
    }

    public function setDateCreation(\DateTime $dateCreation): static
    {
        $this->dateCreation = $dateCreation;

        return $this;
    }

    /**
     * @return Collection<int, CommandeProduit>
     */
    public function getCommandeProduits(): Collection
    {
        return $this->commandeProduits;
    }

    public function addCommandeProduit(CommandeProduit $commandeProduit): static
    {
        if (!$this->commandeProduits->contains($commandeProduit)) {
            $this->commandeProduits->add($commandeProduit);
            $commandeProduit->setProduit($this);
        }

        return $this;
    }

    public function removeCommandeProduit(CommandeProduit $commandeProduit): static
    {
        if ($this->commandeProduits->removeElement($commandeProduit)) {
            // set the owning side to null (unless already changed)
            if ($commandeProduit->getProduit() === $this) {
                $commandeProduit->setProduit(null);
            }
        }

        return $this;
    }

    public function getProduitCategorie(): ?ProduitCategorie
    {
        return $this->produitCategorie;
    }

    public function setProduitCategorie(?ProduitCategorie $produitCategorie): static
    {
        $this->produitCategorie = $produitCategorie;

        return $this;
    }
}
