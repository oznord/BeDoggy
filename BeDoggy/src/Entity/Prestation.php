<?php

namespace App\Entity;

use App\Repository\PrestationRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: PrestationRepository::class)]
class Prestation
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $libelle = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $description = null;

    #[ORM\Column (nullable: true)]
    private ?int $nbSeance = null;

    #[ORM\Column (nullable: true)]
    private ?float $prixSeance = null;

    #[ORM\Column (nullable: true)]
    private ?int $tempsSeance = null;

    /**
     * @var Collection<int, Demande>
     */
    #[ORM\ManyToMany(targetEntity: Demande::class, mappedBy: 'Correspondre')]
    private Collection $demandes;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $image = null;

    public function __construct()
    {
        $this->demandes = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getLibelle(): ?string
    {
        return $this->libelle;
    }

    public function setLibelle(string $libelle): static
    {
        $this->libelle = $libelle;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): static
    {
        $this->description = $description;

        return $this;
    }

    public function getNbSeance(): ?int
    {
        return $this->nbSeance;
    }

    public function setNbSeance(int $nbSeance): static
    {
        $this->nbSeance = $nbSeance;

        return $this;
    }

    public function getPrixSeance(): ?float
    {
        return $this->prixSeance;
    }

    public function setPrixSeance(float $prixSeance): static
    {
        $this->prixSeance = $prixSeance;

        return $this;
    }

    public function getTempsSeance(): ?int
    {
        return $this->tempsSeance;
    }

    public function setTempsSeance(int $tempsSeance): static
    {
        $this->tempsSeance = $tempsSeance;

        return $this;
    }

    /**
     * @return Collection<int, Demande>
     */
    public function getDemandes(): Collection
    {
        return $this->demandes;
    }

    public function addDemande(Demande $demande): static
    {
        if (!$this->demandes->contains($demande)) {
            $this->demandes->add($demande);
            $demande->addCorrespondre($this);
        }

        return $this;
    }

    public function removeDemande(Demande $demande): static
    {
        if ($this->demandes->removeElement($demande)) {
            $demande->removeCorrespondre($this);
        }

        return $this;
    }

    public function getImage(): ?string
    {
        return $this->image;
    }

    public function setImage(?string $image): static
    {
        $this->image = $image;

        return $this;
    }
}
