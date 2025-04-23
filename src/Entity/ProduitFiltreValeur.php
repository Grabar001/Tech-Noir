<?php

namespace App\Entity;

use App\Repository\ProduitFiltreValeurRepository;
use Doctrine\ORM\Mapping as ORM;
use App\Entity\Produit;
use App\Entity\FiltreValeur;

#[ORM\Entity(repositoryClass: ProduitFiltreValeurRepository::class)]
class ProduitFiltreValeur
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'produitFiltreValeurs')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Produit $produit = null;

    #[ORM\ManyToOne(inversedBy: 'produitFiltreValeurs')]
    #[ORM\JoinColumn(nullable: false)]
    private ?FiltreValeur $filtreValeur = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getProduit(): ?Produit
    {
        return $this->produit;
    }

    public function setProduit(?Produit $produit): static
    {
        $this->produit = $produit;
        return $this;
    }

    public function getFiltreValeur(): ?FiltreValeur
    {
        return $this->filtreValeur;
    }

    public function setFiltreValeur(?FiltreValeur $filtreValeur): static
    {
        $this->filtreValeur = $filtreValeur;
        return $this;
    }

    public function __toString(): string
    {
        return $this->filtreValeur?->getValeur() ?? '—';
    }
}