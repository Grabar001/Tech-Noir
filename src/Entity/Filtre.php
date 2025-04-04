<?php

namespace App\Entity;

use App\Repository\FiltreRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: FiltreRepository::class)]
class Filtre
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $nom = null;

    #[ORM\ManyToOne(inversedBy: 'filtres')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Categorie $categorie = null;

    /**
     * @var Collection<int, FiltreValeur>
     */
    #[ORM\OneToMany(mappedBy: 'filtre', targetEntity: FiltreValeur::class, cascade: ['persist'], orphanRemoval: true)]
    private Collection $filtreValeurs;

    public function __construct()
    {
        $this->filtreValeurs = new ArrayCollection();
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

    public function getCategorie(): ?Categorie
    {
        return $this->categorie;
    }

    public function setCategorie(?Categorie $categorie): static
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

    public function addFiltreValeur(FiltreValeur $filtreValeur): static
    {
        if (!$this->filtreValeurs->contains($filtreValeur)) {
            $this->filtreValeurs->add($filtreValeur);
            $filtreValeur->setFiltre($this);
        }

        return $this;
    }

    public function removeFiltreValeur(FiltreValeur $filtreValeur): static
    {
        if ($this->filtreValeurs->removeElement($filtreValeur)) {
            // set the owning side to null (unless already changed)
            if ($filtreValeur->getFiltre() === $this) {
                $filtreValeur->setFiltre(null);
            }
        }

        return $this;
    }

    public function __toString(): string
    {
        return $this->nom ?? '';
    }

    #[ORM\Column(type: 'string', length: 50)]
    private ?string $champ = null;

    public function getChamp(): ?string
    {
        return $this->champ;
    }

    public function setChamp(string $champ): self
    {
        $this->champ = $champ;
        return $this;
    }
}
