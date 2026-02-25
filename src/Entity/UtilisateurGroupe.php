<?php

namespace App\Entity;

use App\Repository\UtilisateurGroupeRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

#[ORM\Entity(repositoryClass: UtilisateurGroupeRepository::class)]
#[UniqueEntity(
    fields: ['referenceGroupe'],
    message: 'Cette référence est déjà utilisée.'
)]
class UtilisateurGroupe
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 60)]
    #[Assert\NotBlank(message: 'Le nom du groupe est obligatoire')]
    #[Assert\Length(
        min: 3,
        max: 50,
        minMessage: 'Le nom du groupe doit contenir au moins {{ limit }} caractères',
        maxMessage: 'Le nom du groupe ne doit pas dépasser {{ limit }} caractères'
    )]
    private ?string $nomGroupe = null;

    /**
     * @var Collection<int, Utilisateur>
     */
    #[ORM\OneToMany(targetEntity: Utilisateur::class, mappedBy: 'utilisateurGroupe')]
    private Collection $utilisateurs;
    
    #[ORM\Column(length: 50, unique: true)]
    #[Assert\NotBlank(message: "La référence du groupe est obligatoire")]
    #[Assert\Length(
        min: 3,
        max: 50,
        minMessage: "La référence doit contenir au moins {{ limit }} caractères",
        maxMessage: "La référence ne doit pas dépasser {{ limit }} caractères"
    )]
    private string $referenceGroupe = "";

    #[ORM\Column(length: 255, nullable: true)]
    #[Assert\Length(
        min: 5,
        max: 255,
        minMessage: "L'adresse doit contenir au moins {{ limit }} caractères",
        maxMessage: "L'adresse ne doit pas dépasser {{ limit }} caractères"
    )]
    private ?string $adresse = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Assert\Regex(
        pattern: '/^[0-9]{5}$/',
        message: "Le code postal doit contenir exactement 5 chiffres"
    )]
    private ?string $codePostal = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Assert\Length(
        min: 2,
        max: 100,
        minMessage: "La ville doit contenir au moins {{ limit }} caractères",
        maxMessage: "La ville ne doit pas dépasser {{ limit }} caractères"
    )]
    private ?string $ville = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Assert\Regex(
        pattern: '/^\+?[0-9\s\-]{6,20}$/',
        message: "Le numéro de téléphone est invalide"
    )]
    private ?string $telephone = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message: "L'email est obligatoire")]
    #[Assert\Email(message: "L'email '{{ value }}' n'est pas valide")]
    private ?string $email = null;

    public function __construct()
    {
        $this->utilisateurs = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNomGroupe(): ?string
    {
        return $this->nomGroupe;
    }

    public function setNomGroupe(string $nomGroupe): static
    {
        $this->nomGroupe = $nomGroupe;

        return $this;
    }

    /**
     * @return Collection<int, Utilisateur>
     */
    public function getUtilisateurs(): Collection
    {
        return $this->utilisateurs;
    }

    public function addUtilisateur(Utilisateur $utilisateur): static
    {
        if (!$this->utilisateurs->contains($utilisateur)) {
            $this->utilisateurs->add($utilisateur);
            $utilisateur->setUtilisateurGroupe($this);
        }

        return $this;
    }

    public function removeUtilisateur(Utilisateur $utilisateur): static
    {
        if ($this->utilisateurs->removeElement($utilisateur)) {
            // set the owning side to null (unless already changed)
            if ($utilisateur->getUtilisateurGroupe() === $this) {
                $utilisateur->setUtilisateurGroupe(null);
            }
        }

        return $this;
    }

    public function getReferenceGroupe(): ?string
    {
        return $this->referenceGroupe;
    }

    public function setReferenceGroupe(string $referenceGroupe): static
    {
        $this->referenceGroupe = $referenceGroupe;

        return $this;
    }

    public function getAdresse(): ?string
    {
        return $this->adresse;
    }

    public function setAdresse(?string $adresse): static
    {
        $this->adresse = $adresse;

        return $this;
    }

    public function getCodePostal(): ?string
    {
        return $this->codePostal;
    }

    public function setCodePostal(?string $codePostal): static
    {
        $this->codePostal = $codePostal;

        return $this;
    }

    public function getVille(): ?string
    {
        return $this->ville;
    }

    public function setVille(?string $ville): static
    {
        $this->ville = $ville;

        return $this;
    }

    public function getTelephone(): ?string
    {
        return $this->telephone;
    }

    public function setTelephone(?string $telephone): static
    {
        $this->telephone = $telephone;

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): static
    {
        $this->email = $email;

        return $this;
    }
}
