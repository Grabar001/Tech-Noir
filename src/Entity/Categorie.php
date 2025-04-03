<?php

namespace App\Entity;

use App\Repository\CategorieRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use App\Entity\Produit;
use Doctrine\ORM\Mapping\PrePersist;
use Doctrine\ORM\Mapping\PreUpdate;
use Symfony\Component\String\Slugger\SluggerInterface;

#[ORM\Entity(repositoryClass: CategorieRepository::class)]
#[ORM\HasLifecycleCallbacks]
class Categorie
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $nom = null;

    #[ORM\Column(length: 255, unique: true)]
    private ?string $slug = null;

    #[ORM\OneToMany(mappedBy: 'categorie', targetEntity: Produit::class)]
    private Collection $produits;

    #[ORM\Column(length: 255)]
    private ?string $image = null;

    /**
     * @var Collection<int, Filtre>
     */
    #[ORM\OneToMany(targetEntity: Filtre::class, mappedBy: 'categorie')]
    private Collection $filtres;

    public function __construct()
    {
        $this->produits = new ArrayCollection();
        $this->filtres = new ArrayCollection();
    }

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

    public function getSlug(): ?string
    {
        return $this->slug;
    }

    public function setSlug(string $slug): static
    {
        $this->slug = $slug;

        return $this;
    }

    public function getProduits(): Collection
    {
        return $this->produits;
    }

    public function addProduit(Produit $produit): static
    {
        if (!$this->produits->contains($produit)) {
            $this->produits[] = $produit;
            $produit->setCategorie($this);
        }

        return $this;
    }

    public function removeProduit(Produit $produit): static
    {
        if ($this->produits->removeElement($produit)) {
            if ($produit->getCategorie() === $this) {
                $produit->setCategorie(null);
            }
        }

        return $this;
    }

    public function __toString(): string
    {
        return $this->nom ?? '—';
    }

    #[ORM\PrePersist]
    #[ORM\PreUpdate]
    public function generateSlug(): void
    {
        if (!$this->slug && $this->nom) {
            $slug = strtolower(trim(preg_replace('/[^a-z0-9]+/i', '-', $this->nom), '-'));
            $this->slug = $slug;
        }
    }

    public function getImage(): ?string
    {
        return $this->image;
    }

    public function setImage(string $image): static
    {
        $this->image = $image;

        return $this;
    }

    /**
     * @return Collection<int, Filtre>
     */
    public function getFiltres(): Collection
    {
        return $this->filtres;
    }

    public function addFiltre(Filtre $filtre): static
    {
        if (!$this->filtres->contains($filtre)) {
            $this->filtres->add($filtre);
            $filtre->setCategorie($this);
        }

        return $this;
    }

    public function removeFiltre(Filtre $filtre): static
    {
        if ($this->filtres->removeElement($filtre)) {
            // set the owning side to null (unless already changed)
            if ($filtre->getCategorie() === $this) {
                $filtre->setCategorie(null);
            }
        }

        return $this;
    }
}