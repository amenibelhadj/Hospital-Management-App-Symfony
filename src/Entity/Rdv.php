<?php

namespace App\Entity;

use App\Repository\RdvRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

use Doctrine\DBAL\Types\Types;

use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: RdvRepository::class)]
class Rdv
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $id_user = null;

    #[ORM\Column(length: 255)]
    private ?string $nom_med = null;

    #[ORM\Column(length: 255)]
   # #[Assert\NotBlank(message:"DATE is required")]
    #[Assert\DateTime()]
    private ?string $date = null;



    #[ORM\Column(length: 255)]
    #[Assert\Time]

    private ?string $heure = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message:"champs 1ere visite obligatoire")]
    
    private ?string $prv = null;

    #[ORM\ManyToOne(inversedBy: 'rdvs')]
    private ?Disponibilite $disponibilite = null;

    /* public function __construct()
    {
        $this->disponibilite = new ArrayCollection();
    } */

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getIdUser(): ?User
    {
        return $this->id_user;
    }

    public function setIdUser(?User $id_user): self
    {
        $this->id_user = $id_user;

        return $this;
    }

    public function getNomMed(): ?string
    {
        return $this->nom_med;
    }

    public function setNomMed(string $nom_med): self
    {
        $this->nom_med = $nom_med;

        return $this;
    }

    public function getDate(): ?string
    {
        return $this->date;
    }

    public function setDate(string $date): self
    {
        $this->date = $date;

        return $this;
    }

    public function getHeure(): ?string
    {
        return $this->heure;
    }

    public function setHeure(string $heure): self
    {
        $this->heure = $heure;

        return $this;
    }

    public function getPrv(): ?string
    {
        return $this->prv;
    }

    public function setPrv(string $prv): self
    {
        $this->prv = $prv;

        return $this;
    }

    public function __toString()
{
    return (string)$this->getId();
}

    public function getDisponibilite(): ?Disponibilite
    {
        return $this->disponibilite;
    }

    public function setDisponibilite(?Disponibilite $disponibilite): self
    {
        $this->disponibilite = $disponibilite;

        return $this;
    }


    
}
