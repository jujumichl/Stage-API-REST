<?php

namespace App\Entity;

use App\Repository\StageRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: StageRepository::class)]
class Stage
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(length:8)]
    private ?int $id = null;

    #[ORM\Column(length:4, nullable: true)]
    private ?int $annee = null;

    #[ORM\Column(type: Types::TEXT)]
    private ?string $descriptifMission = null;

    #[ORM\Column(type: Types::TEXT)]
    private ?string $moyens = null;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: false)]
    private ?Etudiant $numeroEtudiant = null;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: false)]
    private ?Periodestage $idPeriodeStage = null;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: false)]
    private ?Organisation $numeroOrganisation = null;

    /**
     * @var Collection<int, Referentiel>
     */
    #[ORM\ManyToMany(targetEntity: Referentiel::class, inversedBy: 'idStage')]
    private Collection $idRef;

    public function __construct()
    {
        $this->idRef = new ArrayCollection();
    }


    public function getId(): ?int
    {
        return $this->id;
    }

    public function getAnnee(): ?int
    {
        return $this->annee;
    }

    public function setAnnee(?int $annee): static
    {
        $this->annee = $annee;

        return $this;
    }

    public function getDescriptifMission(): ?string
    {
        return $this->descriptifMission;
    }

    public function setDescriptifMission(string $descriptifMission): static
    {
        $this->descriptifMission = $descriptifMission;

        return $this;
    }

    public function getMoyens(): ?string
    {
        return $this->moyens;
    }

    public function setMoyens(string $moyens): static
    {
        $this->moyens = $moyens;

        return $this;
    }

    public function getNumeroEtudiant(): ?Etudiant
    {
        return $this->numeroEtudiant;
    }

    public function setNumeroEtudiant(?Etudiant $numeroEtudiant): static
    {
        $this->numeroEtudiant = $numeroEtudiant;

        return $this;
    }

    public function getIdPeriodeStage(): ?Periodestage
    {
        return $this->idPeriodeStage;
    }

    public function setIdPeriodeStage(?Periodestage $idPeriodeStage): static
    {
        $this->idPeriodeStage = $idPeriodeStage;

        return $this;
    }

    public function getNumeroOrganisation(): ?Organisation
    {
        return $this->numeroOrganisation;
    }

    public function setNumeroOrganisation(?Organisation $numeroOrganisation): static
    {
        $this->numeroOrganisation = $numeroOrganisation;

        return $this;
    }

    /**
     * @return Collection<int, Referentiel>
     */
    public function getIdRef(): Collection
    {
        return $this->idRef;
    }

    public function addIdRef(Referentiel $idRef): static
    {
        if (!$this->idRef->contains($idRef)) {
            $this->idRef->add($idRef);
        }

        return $this;
    }

    public function removeIdRef(Referentiel $idRef): static
    {
        $this->idRef->removeElement($idRef);

        return $this;
    }

}
