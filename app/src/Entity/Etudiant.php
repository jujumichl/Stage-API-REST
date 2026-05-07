<?php

namespace App\Entity;

use App\Repository\EtudiantRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Serializer\Attribute\Groups;


#[ORM\Entity(repositoryClass: EtudiantRepository::class)]
class Etudiant implements UserInterface, PasswordAuthenticatedUserInterface
{
    #[Assert\NotBlank]
    #[Assert\Type('int')]
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(length:8)]
    #[Groups(['stages.get'])]
    private ?int $id = null;

    #[ORM\Column(length: 50)]
    #[Groups(['stages.get'])]
    private ?string $nom = null;

    #[ORM\Column(length: 50)]
    private ?string $prenom = null;

    #[ORM\Column(length: 100)]
    #[Groups(['stages.get'])]
    private ?string $rue = null;

    #[ORM\Column(length: 6)]
    #[Groups(['stages.get'])]
    private ?string $codePostal = null;

    #[ORM\Column(length: 100)]
    #[Groups(['stages.get'])]
    private ?string $ville = null;

    #[ORM\Column(length: 100)]
    #[Groups(['stages.get'])]
    private ?string $email = null;

    #[ORM\Column(length:4)]
    private ?int $anneePromo = null;
    
    #[ORM\Column(length: 100)]
    private ?string $mdp = null;
    
    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: false)]
    #[Groups(['stages.get'])]
    private ?Specialite $specialite = null;

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

    public function getPrenom(): ?string
    {
        return $this->prenom;
    }

    public function setPrenom(string $prenom): static
    {
        $this->prenom = $prenom;

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): static
    {
        $this->email = $email;

        return $this;
    }

    public function getAnneePromo(): ?int
    {
        return $this->anneePromo;
    }

    public function setAnneePromo(int $anneePromo): static
    {
        $this->anneePromo = $anneePromo;

        return $this;
    }


    public function getCodePostal(): ?string
    {
        return $this->codePostal;
    }

    public function setCodePostal(string $codePostal): static
    {
        $this->codePostal = $codePostal;

        return $this;
    }

    public function getRue(): ?string
    {
        return $this->rue;
    }

    public function setRue(string $rue): static
    {
        $this->rue = $rue;

        return $this;
    }

    public function getVille(): ?string
    {
        return $this->ville;
    }

    public function setVille(string $ville): static
    {
        $this->ville = $ville;

        return $this;
    }

    public function getSpecialite(): ?Specialite
    {
        return $this->specialite;
    }

    public function setSpecialite(?Specialite $specialite_id): static
    {
        $this->specialite = $specialite_id;

        return $this;
    }

    public function getMdp(): ?string
    {
        return $this->mdp;
    }

    public function setMdp(string $mdp): static
    {
        $this->mdp = $mdp;

        return $this;
    }

    public function getRoles():array
    {
        return ['ROLE_ETUDIANT'];
    }

    public function getPassword():string {
        return $this->getmdp();
    }

    public function getUserIdentifier(): string {
        return $this->getEmail();
    }

    public function eraseCredentials(): void
    {
        return;
    }
}
