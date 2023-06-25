<?php

namespace App\Entity;

use App\Repository\FicheRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;



#[ORM\Entity(repositoryClass: FicheRepository::class)]

class Fiche
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message:"Remplir le champ Nom")]
    private ?string $nom = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message:"Remplir le champ Prenom")]
    private ?string $prenom = null;

    #[ORM\Column]
    #[Assert\NotBlank(message:"Remplir le champ Age")]
    private ?int $age = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message:"Remplir le champ Poids")]
    private ?string $poids = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message:"Remplir le champ Taille")]
    private ?string $taille = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message:"Remplir le champ Email")]
    private ?string $email = null;

    #[ORM\OneToMany(mappedBy: 'fiche', targetEntity: Consultation::class)]
    private Collection $Consultation;

    public function __construct()
    {
        $this->Consultation = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom(string $nom): self
    {
        $this->nom = $nom;

        return $this;
    }

    public function getPrenom(): ?string
    {
        return $this->prenom;
    }

    public function setPrenom(string $prenom): self
    {
        $this->prenom = $prenom;

        return $this;
    }

    public function getAge(): ?int
    {
        return $this->age;
    }

    public function setAge(int $age): self
    {
        $this->age = $age;

        return $this;
    }

    public function getPoids(): ?string
    {
        return $this->poids;
    }

    public function setPoids(string $poids): self
    {
        $this->poids = $poids;

        return $this;
    }

    public function getTaille(): ?string
    {
        return $this->taille;
    }

    public function setTaille(string $taille): self
    {
        $this->taille = $taille;

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    /**
     * @return Collection<int, Consultation>
     */
    public function getConsultation(): Collection
    {
        return $this->Consultation;
    }

    public function addConsultation(Consultation $consultation): self
    {
        if (!$this->Consultation->contains($consultation)) {
            $this->Consultation->add($consultation);
            $consultation->setFiche($this);
        }

        return $this;
    }

    public function removeConsultation(Consultation $consultation): self
    {
        if ($this->Consultation->removeElement($consultation)) {
            // set the owning side to null (unless already changed)
            if ($consultation->getFiche() === $this) {
                $consultation->setFiche(null);
            }
        }

        return $this;
    }
    
    public function __toString()
{
    return (string)$this->getId();
}
}

