<?php

namespace App\Entity;

use App\Repository\SpecialiteRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Attribute\Groups;

#[ORM\Entity(repositoryClass: SpecialiteRepository::class)]
class Specialite
{
    #[ORM\Id]
    #[ORM\GeneratedValue(strategy:'NONE')]
    #[ORM\Column(length:1)]
    #[Groups(['stages.get'])]
    private ?string $id = null;

    #[ORM\Column(length: 4, nullable: true)]
    #[Groups(['stages.get'])]
    private ?string $sigle = null;

    #[ORM\Column(length: 60)]
    #[Groups(['stages.get'])]
    private ?string $intitule = null;

    public function getId(): ?string
    {
        return $this->id;
    }

    public function getSigle(): ?string
    {
        return $this->sigle;
    }

    public function setSigle(?string $sigle): static
    {
        $this->sigle = $sigle;

        return $this;
    }

    public function getIntitule(): ?string
    {
        return $this->intitule;
    }

    public function setIntitule(string $intitule): static
    {
        $this->intitule = $intitule;

        return $this;
    }
}
