<?php

namespace App\Entity;

use App\Repository\CouponCategorieRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: CouponCategorieRepository::class)]
class CouponCategorie
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $categorie = null;

    /**
     * @var Collection<int, CouponReduction>
     */
    #[ORM\OneToMany(targetEntity: CouponReduction::class, mappedBy: 'couponCategorie')]
    private Collection $couponReductions;

    public function __construct()
    {
        $this->couponReductions = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCategorie(): ?string
    {
        return $this->categorie;
    }

    public function setCategorie(string $categorie): static
    {
        $this->categorie = $categorie;

        return $this;
    }

    /**
     * @return Collection<int, CouponReduction>
     */
    public function getCouponReductions(): Collection
    {
        return $this->couponReductions;
    }

    public function addCouponReduction(CouponReduction $couponReduction): static
    {
        if (!$this->couponReductions->contains($couponReduction)) {
            $this->couponReductions->add($couponReduction);
            $couponReduction->setCouponCategorie($this);
        }

        return $this;
    }

    public function removeCouponReduction(CouponReduction $couponReduction): static
    {
        if ($this->couponReductions->removeElement($couponReduction)) {
            // set the owning side to null (unless already changed)
            if ($couponReduction->getCouponCategorie() === $this) {
                $couponReduction->setCouponCategorie(null);
            }
        }

        return $this;
    }
}
