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

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: false)]
    private ?Etudiant $etudiant = null;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: false)]
    private ?Periode $periode = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $descriptifMissions = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $moyens = null;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: false)]
    private ?Organisation $organisation = null;

    /**
     * @var Collection<int, Competence>
     */
    /* #[ORM\ManyToMany(targetEntity: Competence::class)]
    private Collection $competence;

    public function __construct()
    {
        $this->competence = new ArrayCollection();
    } */



    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDescriptifMissions(): ?string
    {
        return $this->descriptifMissions;
    }

    public function setDescriptifMissions(string $descriptifMissions): static
    {
        $this->descriptifMissions = $descriptifMissions;

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

    public function getEtudiant(): ?Etudiant
    {
        return $this->etudiant;
    }

    public function setEtudiant(?Etudiant $Etudiant): static
    {
        $this->etudiant = $Etudiant;

        return $this;
    }

    public function getIdPeriodeStage(): ?Periode
    {
        return $this->periode;
    }

    public function setIdPeriodeStage(?Periode $idPeriodeStage): static
    {
        $this->periode = $idPeriodeStage;

        return $this;
    }

    public function getNumeroOrganisation(): ?Organisation
    {
        return $this->organisation;
    }

    public function setNumeroOrganisation(?Organisation $numeroOrganisation): static
    {
        $this->organisation = $numeroOrganisation;

        return $this;
    }

    /**
     * @return Collection<int, Competence>
     */
    /* public function getCompetence(): Collection
    {
        return $this->competence;
    }

    public function addCompetence(Competence $competence): static
    {
        if (!$this->competence->contains($competence)) {
            $this->competence->add($competence);
        }

        return $this;
    }

    public function removeCompetence(Competence $competence): static
    {
        $this->competence->removeElement($competence);

        return $this;
    } */

}
