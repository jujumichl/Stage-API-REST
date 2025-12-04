<?php

namespace App\Entity;

use App\Repository\StageRepository;
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

}
