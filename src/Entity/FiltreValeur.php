<?php

namespace App\Entity;

use App\Repository\FiltreValeurRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use App\Entity\Filtre;
use App\Entity\Produit;

#[ORM\Entity(repositoryClass: FiltreValeurRepository::class)]
class FiltreValeur
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $valeur = null;

    #[ORM\ManyToOne(inversedBy: 'filtreValeurs')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Filtre $filtre = null;

    #[ORM\ManyToMany(targetEntity: Produit::class, mappedBy: 'filtreValeurs')]
    private Collection $produits;

    public function __construct()
    {
        $this->produits = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getValeur(): ?string
    {
        return $this->valeur;
    }

    public function setValeur(string $valeur): static
    {
        $this->valeur = $valeur;
        return $this;
    }

    public function getFiltre(): ?Filtre
    {
        return $this->filtre;
    }

    public function setFiltre(?Filtre $filtre): static
    {
        $this->filtre = $filtre;
        return $this;
    }

    public function getProduits(): Collection
    {
        return $this->produits;
    }

    public function __toString(): string
    {
        return $this->valeur ?? '';
    }
}