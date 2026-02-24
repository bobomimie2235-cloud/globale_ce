<?php

namespace App\Entity;

use App\Repository\UtilisateurAdresseRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: UtilisateurAdresseRepository::class)]
class UtilisateurAdresse
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message: "Le nom est obligatoire")]
    #[Assert\Length(
        min: 2,
        max: 50,
        minMessage: "Le nom doit contenir au moins {{ limit }} caractères",
        maxMessage: "Le nom ne doit pas dépasser {{ limit }} caractères"
    )]
    private ?string $nom = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message: "Le prénom est obligatoire")]
    #[Assert\Length(
        min: 2,
        max: 50,
        minMessage: "Le prénom doit contenir au moins {{ limit }} caractères",
        maxMessage: "Le prénom ne doit pas dépasser {{ limit }} caractères"
    )]
    private ?string $prenom = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message: "L'adresse est obligatoire")]
    #[Assert\Length(
        min: 5,
        max: 255,
        minMessage: "L'adresse doit contenir au moins {{ limit }} caractères",
        maxMessage: "L'adresse ne doit pas dépasser {{ limit }} caractères"
    )]
    private ?string $adresse = null;

    #[ORM\Column(length: 5)]
    #[Assert\NotBlank(message: "Le code postal est obligatoire")]
    #[Assert\Regex(
        pattern: '/^[0-9]{5}$/',
        message: "Le code postal doit contenir exactement 5 chiffres"
    )]
    private ?string $codePostal = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message: "La ville est obligatoire")]
    #[Assert\Length(
        min: 2,
        max: 100,
        minMessage: "La ville doit contenir au moins {{ limit }} caractères",
        maxMessage: "La ville ne doit pas dépasser {{ limit }} caractères"
    )]
    private ?string $ville = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message: "Le pays est obligatoire")]
    #[Assert\Length(
        min: 2,
        max: 50,
        minMessage: "Le pays doit contenir au moins {{ limit }} caractères",
        maxMessage: "Le pays ne doit pas dépasser {{ limit }} caractères"
    )]
    private ?string $pays = null;

    #[ORM\ManyToOne(inversedBy: 'utilisateurAdresses')]
    private ?Utilisateur $utilisateur = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom(string $nom): static
    {
        $this->nom = $nom;

        return $this;
    }

    public function getPrenom(): ?string
    {
        return $this->prenom;
    }

    public function setPrenom(string $prenom): static
    {
        $this->prenom = $prenom;

        return $this;
    }

    public function getAdresse(): ?string
    {
        return $this->adresse;
    }

    public function setAdresse(string $adresse): static
    {
        $this->adresse = $adresse;

        return $this;
    }

    public function getCodePostal(): ?string
    {
        return $this->codePostal;
    }

    public function setCodePostal(string $codePostal): static
    {
        $this->codePostal = $codePostal;

        return $this;
    }

    public function getVille(): ?string
    {
        return $this->ville;
    }

    public function setVille(string $ville): static
    {
        $this->ville = $ville;

        return $this;
    }

    public function getPays(): ?string
    {
        return $this->pays;
    }

    public function setPays(string $pays): static
    {
        $this->pays = $pays;

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
