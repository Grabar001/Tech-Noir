<?php

namespace App\Entity;

use App\Repository\FiltreValeurRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use App\Entity\Filtre;
use App\Entity\ProduitFiltreValeur;

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

    /**
     * @var Collection<int, ProduitFiltreValeur>
     */
    #[ORM\OneToMany(
        mappedBy: 'filtreValeur',
        targetEntity: ProduitFiltreValeur::class,
        cascade: ['persist'],
        orphanRemoval: true
    )]
    private Collection $produitFiltreValeurs;

    public function __construct()
    {
        $this->produitFiltreValeurs = new ArrayCollection();
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

    /**
     * @return Collection<int, ProduitFiltreValeur>
     */
    public function getProduitFiltreValeurs(): Collection
    {
        return $this->produitFiltreValeurs;
    }

    public function addProduitFiltreValeur(ProduitFiltreValeur $produitFiltreValeur): static
    {
        if (!$this->produitFiltreValeurs->contains($produitFiltreValeur)) {
            $this->produitFiltreValeurs->add($produitFiltreValeur);
            $produitFiltreValeur->setFiltreValeur($this);
        }

        return $this;
    }

    public function removeProduitFiltreValeur(ProduitFiltreValeur $produitFiltreValeur): static
    {
        if ($this->produitFiltreValeurs->removeElement($produitFiltreValeur)) {
            if ($produitFiltreValeur->getFiltreValeur() === $this) {
                $produitFiltreValeur->setFiltreValeur(null);
            }
        }

        return $this;
    }

    public function __toString(): string
    {
        return $this->valeur ?? '';
    }
}