<?php

namespace App\Entity;

use App\Repository\ProduitRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\DBAL\Types\Types;

#[ORM\Entity(repositoryClass: ProduitRepository::class)]
#[ORM\HasLifecycleCallbacks]
class Produit
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $nom = null;

    #[ORM\Column(type: Types::TEXT)]
    private ?string $description = null;

    #[ORM\Column]
    private ?float $prix = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $image = null;

    #[ORM\Column(type: 'integer', nullable: true)]
    #[Assert\Range(min: 0, max: 99)]
    private ?int $reduction = null;

    #[ORM\Column]
    private ?bool $enStock = null;

    #[ORM\Column]
    private ?bool $isNew = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $marque = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $memoire = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $cpu = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $ecran = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $resolution = null;

    #[ORM\Column(length: 255, unique: true)]
    private ?string $slug = null;

    #[ORM\ManyToOne(inversedBy: 'produits')]
    #[ORM\JoinColumn(nullable: true)]
    private ?Categorie $categorie = null;

    #[ORM\OneToMany(mappedBy: 'produit', targetEntity: ProduitFiltreValeur::class, cascade: ['persist'], orphanRemoval: true)]
    private Collection $produitFiltreValeurs;

    public function __construct()
    {
        $this->produitFiltreValeurs = new ArrayCollection();
    }

    public function getId(): ?int { return $this->id; }

    public function getNom(): ?string { return $this->nom; }
    public function setNom(string $nom): static { $this->nom = $nom; return $this; }

    public function getDescription(): ?string { return $this->description; }
    public function setDescription(string $description): static { $this->description = $description; return $this; }

    public function getPrix(): ?float { return $this->prix; }
    public function setPrix(float $prix): static { $this->prix = $prix; return $this; }

    public function getImage(): ?string { return $this->image; }
    public function setImage(?string $image): static { $this->image = $image; return $this; }

    public function getReduction(): ?int { return $this->reduction; }
    public function setReduction(?int $reduction): static { $this->reduction = $reduction; return $this; }

    public function isEnStock(): ?bool { return $this->enStock; }
    public function setEnStock(bool $enStock): static { $this->enStock = $enStock; return $this; }

    public function isNew(): ?bool { return $this->isNew; }
    public function setIsNew(bool $isNew): static { $this->isNew = $isNew; return $this; }

    public function getMarque(): ?string { return $this->marque; }
    public function setMarque(?string $marque): static { $this->marque = $marque; return $this; }

    public function getMemoire(): ?string { return $this->memoire; }
    public function setMemoire(?string $memoire): static { $this->memoire = $memoire; return $this; }

    public function getCpu(): ?string { return $this->cpu; }
    public function setCpu(?string $cpu): static { $this->cpu = $cpu; return $this; }

    public function getEcran(): ?string { return $this->ecran; }
    public function setEcran(?string $ecran): static { $this->ecran = $ecran; return $this; }

    public function getResolution(): ?string { return $this->resolution; }
    public function setResolution(?string $resolution): static { $this->resolution = $resolution; return $this; }

    public function getCategorie(): ?Categorie { return $this->categorie; }
    public function setCategorie(?Categorie $categorie): static { $this->categorie = $categorie; return $this; }

    public function getSlug(): ?string { return $this->slug; }
    public function setSlug(string $slug): static { $this->slug = $slug; return $this; }

    #[ORM\PrePersist]
    #[ORM\PreUpdate]
    public function generateSlug(): void
    {
        if (!$this->slug && $this->nom) {
            $this->slug = strtolower(trim(preg_replace('/[^a-z0-9]+/i', '-', $this->nom), '-'));
        }
    }

    public function getPrixAvecReduction(): float
    {
        if ($this->reduction) {
            return round($this->prix - ($this->prix * $this->reduction / 100), 2);
        }
        return $this->prix;
    }

    public function getProduitFiltreValeurs(): Collection
    {
        return $this->produitFiltreValeurs;
    }

    public function addProduitFiltreValeur(ProduitFiltreValeur $valeur): static
    {
        if (!$this->produitFiltreValeurs->contains($valeur)) {
            $this->produitFiltreValeurs->add($valeur);
            $valeur->setProduit($this);
        }
        return $this;
    }

    public function removeProduitFiltreValeur(ProduitFiltreValeur $valeur): static
    {
        if ($this->produitFiltreValeurs->removeElement($valeur)) {
            if ($valeur->getProduit() === $this) {
                $valeur->setProduit(null);
            }
        }
        return $this;
    }
}