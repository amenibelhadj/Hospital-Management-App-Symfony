<?php

namespace App\Entity;

class FitreRecherche{


    /**
     * @var string|null
     */
    private $titre;

    /**
     * @return string|null
     */
    public function getTitre(): ?string
    {
        return $this->titre;
    }

    /**
     * @param string|null $titre
     */
    public function setTitre(?string $titre): void
    {
        $this->titre = $titre;
    }

}