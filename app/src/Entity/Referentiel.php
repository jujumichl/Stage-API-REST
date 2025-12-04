<?php

namespace App\Entity;

use App\Repository\ReferentielRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ReferentielRepository::class)]
class Referentiel
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $libelle = null;

    /**
     * @var Collection<int, Stage>
     */
    #[ORM\ManyToMany(targetEntity: Stage::class, mappedBy: 'idRef')]
    private Collection $idStage;

    public function __construct()
    {
        $this->idStage = new ArrayCollection();
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

    /**
     * @return Collection<int, Stage>
     */
    public function getIdStage(): Collection
    {
        return $this->idStage;
    }

    public function addIdStage(Stage $idStage): static
    {
        if (!$this->idStage->contains($idStage)) {
            $this->idStage->add($idStage);
            $idStage->addIdRef($this);
        }

        return $this;
    }

    public function removeIdStage(Stage $idStage): static
    {
        if ($this->idStage->removeElement($idStage)) {
            $idStage->removeIdRef($this);
        }

        return $this;
    }
}
