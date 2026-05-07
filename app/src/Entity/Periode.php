<?php

namespace App\Entity;

use App\Repository\PeriodeRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Serializer\Attribute\Groups;

#[ORM\Entity(repositoryClass: PeriodeRepository::class)]
class Periode
{
    #[Assert\NotBlank]
    #[Assert\Type('int')]
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(length:4)]
    #[Groups(['stages.get'])]
    private ?int $id = null;

    #[ORM\Column(type: Types::DATE_MUTABLE, nullable: true)]
    #[Groups(['stages.get'])]
    private ?\DateTime $dateDebut = null;

    #[ORM\Column(type: Types::DATE_MUTABLE, nullable: true)]
    #[Groups(['stages.get'])]
    private ?\DateTime $dateFin = null;

    #[ORM\Column(nullable: true, length:4)]
    #[Groups(['stages.get'])]
    private ?int $numAnneeForm = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDateDebut(): ?\DateTime
    {
        return $this->dateDebut;
    }

    public function setDateDebut(?\DateTime $dateDebut): static
    {
        $this->dateDebut = $dateDebut;

        return $this;
    }

    public function getDateFin(): ?\DateTime
    {
        return $this->dateFin;
    }

    public function setDateFin(?\DateTime $dateFin): static
    {
        $this->dateFin = $dateFin;

        return $this;
    }

    public function getNumAnneeForm(): ?int
    {
        return $this->numAnneeForm;
    }

    public function setNumAnneeForm(?int $numAnneeForm): static
    {
        $this->numAnneeForm = $numAnneeForm;

        return $this;
    }
}
