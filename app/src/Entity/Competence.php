<?php

namespace App\Entity;

use App\Repository\CompetenceRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Attribute\Groups;

#[ORM\Entity(repositoryClass: CompetenceRepository::class)]
class Competence
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['stages.get'])]
    private ?int $id = null;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: false)]
    #[Groups(['stages.get'])]
    private ?Bloc $bloc = null;

    #[ORM\Column]
    #[Groups(['stages.get'])]
    private ?int $numeroDansBloc = null;

    #[ORM\Column(length: 255)]
    #[Groups(['stages.get'])]
    private ?string $libelle = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getBloc(): ?Bloc
    {
        return $this->bloc;
    }

    public function setBloc(?Bloc $bloc): static
    {
        $this->bloc = $bloc;

        return $this;
    }

    public function getNumeroDansBloc(): ?int
    {
        return $this->numeroDansBloc;
    }

    public function setNumeroDansBloc(int $numeroDansBloc): static
    {
        $this->numeroDansBloc = $numeroDansBloc;

        return $this;
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
}
