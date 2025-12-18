<?php

namespace App\Entity;

use App\Repository\StageRepository;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Symfony\Component\Validator\Constraints as Assert;


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

    #[Assert\NotBlank]
    #[Assert\Type('string')]
    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $descriptifMissions = null;

    #[Assert\NotBlank]
    #[Assert\Type('string')]
    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $moyens = null;

    
    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: false)]
    private ?Organisation $organisation = null;

    /**
     * @var Collection<int, Competence>
     */ //////////////////////////////////////////////////// FAIRE DES GROUPES POUR NE PAS INTEGRER LES COMPETENCES
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

    public function getPeriodeStage(): ?Periode
    {
        return $this->periode;
    }

    public function setPeriodeStage(?Periode $periodeStage): static
    {
        $this->periode = $periodeStage;

        return $this;
    }

    public function getOrganisation(): ?Organisation
    {
        return $this->organisation;
    }

    public function setOrganisation(?Organisation $organisation): static
    {
        $this->organisation = $organisation;

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
