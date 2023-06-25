<?php

namespace App\Entity;

class nameSearch
{

   private $nom;

   
   public function getNom(): ?string
   {
       return $this->nom;
   }

   public function setNom(string $nom): self
   {
       $this->nom = $nom;

       return $this;
   }
}