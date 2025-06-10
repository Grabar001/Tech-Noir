<?php

namespace App\Entity;

use App\Entity\CommandeProduit;
use App\Repository\ProduitRepository;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: ProduitRepository::class)]
class Produit
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private ?int $id = null;

    #[ORM\Column(type: 'string', length: 255)]
    private string $nom;

    #[ORM\Column(type: 'text')]
    private string $description;

    #[ORM\Column(type: 'float')]
    private float $prix;

    #[ORM\Column(type: 'integer', nullable: true)]
    private ?int $reduction = null;

    #[ORM\Column(type: 'boolean')]
    private bool $enStock = true;

    #[ORM\Column(type: 'boolean')]
    private bool $nouveau = false;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    private ?string $image = null;

    #[ORM\OneToMany(mappedBy: 'produit', targetEntity: CommandeProduit::class, cascade: ['remove'])]
    private Collection $commandeProduits;

    #[ORM\ManyToOne(targetEntity: Categorie::class, inversedBy: 'produits')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Categorie $categorie = null;

    #[ORM\ManyToMany(targetEntity: FiltreValeur::class, inversedBy: 'produits')]
    private Collection $filtreValeurs;

  public function __construct()
{
    $this->filtreValeurs = new ArrayCollection();
    $this->commandeProduits = new ArrayCollection();
}

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom(string $nom): self
    {
        $this->nom = $nom;
        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;
        return $this;
    }

    public function getPrix(): ?float
    {
        return $this->prix;
    }

    public function setPrix(float $prix): self
    {
        $this->prix = $prix;
        return $this;
    }

    public function getReduction(): ?int
    {
        return $this->reduction;
    }

    public function setReduction(?int $reduction): self
    {
        $this->reduction = $reduction;
        return $this;
    }

    public function isEnStock(): bool
    {
        return $this->enStock;
    }

    public function setEnStock(bool $enStock): self
    {
        $this->enStock = $enStock;
        return $this;
    }

    public function isNouveau(): bool
    {
        return $this->nouveau;
    }

    public function setNouveau(bool $nouveau): self
    {
        $this->nouveau = $nouveau;
        return $this;
    }

    public function getImage(): ?string
    {
        return $this->image;
    }

    public function setImage(?string $image): self
    {
        $this->image = $image;
        return $this;
    }

    public function getCategorie(): ?Categorie
    {
        return $this->categorie;
    }

    public function setCategorie(?Categorie $categorie): self
    {
        $this->categorie = $categorie;
        return $this;
    }

    /**
     * @return Collection<int, FiltreValeur>
     */
    public function getFiltreValeurs(): Collection
    {
        return $this->filtreValeurs;
    }

    public function addFiltreValeur(FiltreValeur $valeur): self
    {
        if (!$this->filtreValeurs->contains($valeur)) {
            $this->filtreValeurs[] = $valeur;
        }
        return $this;
    }

    public function removeFiltreValeur(FiltreValeur $valeur): self
    {
        $this->filtreValeurs->removeElement($valeur);
        return $this;
    }

    public function getPrixAvecReduction(): float
    {
        if ($this->reduction && $this->reduction > 0) {
            return $this->prix * (1 - $this->reduction / 100);
        }
        return $this->prix;
    }

    public function getCommandeProduits(): Collection
{
    return $this->commandeProduits;
}

public function addCommandeProduit(CommandeProduit $commandeProduit): self
{
    if (!$this->commandeProduits->contains($commandeProduit)) {
        $this->commandeProduits[] = $commandeProduit;
        $commandeProduit->setProduit($this);
    }

    return $this;
}

public function removeCommandeProduit(CommandeProduit $commandeProduit): self
{
    if ($this->commandeProduits->removeElement($commandeProduit)) {
        if ($commandeProduit->getProduit() === $this) {
            $commandeProduit->setProduit(null);
        }
    }

    return $this;
}
}