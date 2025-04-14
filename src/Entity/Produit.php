<?php

namespace App\Entity;

use App\Repository\ProduitRepository;
use Symfony\Component\String\Slugger\SluggerInterface;
use Doctrine\ORM\Mapping\PrePersist;
use Doctrine\ORM\Mapping\PreUpdate;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use App\Entity\Categorie;
use Symfony\Component\Validator\Constraints as Assert;

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
    private ?string $Description = null;

    #[ORM\Column]
    private ?float $prix = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $Image = null;

    #[ORM\Column(type: 'integer', nullable: true)]
    #[Assert\Range(
        min: 0,
        max: 99,
        notInRangeMessage: 'La réduction doit être entre {{ min }}% et {{ max }}%.'
    )]
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

    #[ORM\ManyToOne(inversedBy: 'produits')]
    #[ORM\JoinColumn(nullable: true)]
    private ?Categorie $categorie = null;

    #[ORM\Column(length: 255, unique: true)]
    private ?string $slug = null;

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
        $this->Nom = $nom;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->Description;
    }

    public function setDescription(string $Description): static
    {
        $this->Description = $Description;

        return $this;
    }

    public function getPrix(): ?float
    {
        return $this->prix;
    }

    public function setPrix(float $prix): static
    {
        $this->prix = $prix;

        return $this;
    }

    public function getImage(): ?string
    {
        return $this->Image;
    }

    public function setImage(string $Image): static
    {
        $this->Image = $Image;

        return $this;
    }

    public function getReduction(): ?int
    {
        return $this->reduction;
    }

    public function setReduction(?int $reduction): static
    {
        $this->reduction = $reduction;

        return $this;
    }

    public function isEnStock(): ?bool
    {
        return $this->enStock;
    }

    public function setEnStock(bool $enStock): static
    {
        $this->enStock = $enStock;

        return $this;
    }

    public function isNew(): ?bool
    {
        return $this->isNew;
    }

    public function setIsNew(bool $isNew): static
    {
        $this->isNew = $isNew;

        return $this;
    }

    public function getCategorie(): ?Categorie
    {
        return $this->categorie;
    }

    public function setCategorie(?Categorie $categorie): static
    {
        $this->categorie = $categorie;

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

    #[ORM\PrePersist]
    #[ORM\PreUpdate]
    public function generateSlug(): void
    {
        if (!$this->slug && $this->nom) {
            $slug = strtolower(trim(preg_replace('/[^a-z0-9]+/i', '-', $this->nom), '-'));
            $this->slug = $slug;
        }
    }

    public function getPrixAvecReduction(): float
{
    if ($this->reduction) {
        return round($this->prix - ($this->prix * $this->reduction / 100), 2);
    }

    return $this->prix;
}
}
