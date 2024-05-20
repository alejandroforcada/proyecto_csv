<?php

namespace App\Entity;

use App\Repository\OrdinadorsRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: OrdinadorsRepository::class)]
class Ordinadors
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $Nom = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $Entitat = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $Codi_inventari = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $Estat = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $Tipus = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $Codi_article = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $Descripcio_codi_article = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $Numero_serie = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $Fabricant = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $Model = null;


    #[ORM\Column(length: 255, nullable: true)]
    private ?string $Sistema_operatiu_nom = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $Sistema_operatiu_versio = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $Espai_desti = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $Descripcio_espai_desti = null;

    #[ORM\Column(nullable: true)]
    private ?bool $elegido = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setId(?int $Id): static
    {
        $this->Id = $Id;

        return $this;
    }
   

    public function getNom(): ?string
    {
        return $this->Nom;
    }

    public function setNom(?string $Nom): static
    {
        $this->Nom = $Nom;

        return $this;
    }

    public function getEntitat(): ?string
    {
        return $this->Entitat;
    }

    public function setEntitat(?string $Entitat): static
    {
        $this->Entitat = $Entitat;

        return $this;
    }

    public function getCodiInventari(): ?string
    {
        return $this->Codi_inventari;
    }

    public function setCodiInventari(?string $Codi_inventari): static
    {
        $this->Codi_inventari = $Codi_inventari;

        return $this;
    }

    public function getEstat(): ?string
    {
        return $this->Estat;
    }

    public function setEstat(?string $Estat): static
    {
        $this->Estat = $Estat;

        return $this;
    }

    public function getTipus(): ?string
    {
        return $this->Tipus;
    }

    public function setTipus(?string $Tipus): static
    {
        $this->Tipus = $Tipus;

        return $this;
    }

    public function getCodiArticle(): ?string
    {
        return $this->Codi_article;
    }

    public function setCodiArticle(?string $Codi_article): static
    {
        $this->Codi_article = $Codi_article;

        return $this;
    }

    public function getDescripcioCodiArticle(): ?string
    {
        return $this->Descripcio_codi_article;
    }

    public function setDescripcioCodiArticle(?string $Descripcio_codi_article): static
    {
        $this->Descripcio_codi_article = $Descripcio_codi_article;

        return $this;
    }

    public function getNumeroSerie(): ?string
    {
        return $this->Numero_serie;
    }

    public function setNumeroSerie(?string $Numero_serie): static
    {
        $this->Numero_serie = $Numero_serie;

        return $this;
    }

    public function getFabricant(): ?string
    {
        return $this->Fabricant;
    }

    public function setFabricant(?string $Fabricant): static
    {
        $this->Fabricant = $Fabricant;

        return $this;
    }

    public function getModel(): ?string
    {
        return $this->Model;
    }

    public function setModel(?string $Model): static
    {
        $this->Model = $Model;

        return $this;
    }



    public function getSistemaOperatiuNom(): ?string
    {
        return $this->Sistema_operatiu_nom;
    }

    public function setSistemaOperatiuNom(?string $Sistema_operatiu_nom): static
    {
        $this->Sistema_operatiu_nom = $Sistema_operatiu_nom;

        return $this;
    }

    public function getSistemaOperatiuVersio(): ?string
    {
        return $this->Sistema_operatiu_versio;
    }

    public function setSistemaOperatiuVersio(?string $Sistema_operatiu_versio): static
    {
        $this->Sistema_operatiu_versio = $Sistema_operatiu_versio;

        return $this;
    }

    public function getEspaiDesti(): ?string
    {
        return $this->Espai_desti;
    }

    public function setEspaiDesti(?string $Espai_desti): static
    {
        $this->Espai_desti = $Espai_desti;

        return $this;
    }

    public function getDescripcioEspaiDesti(): ?string
    {
        return $this->Descripcio_espai_desti;
    }

    public function setDescripcioEspaiDesti(?string $Descripcio_espai_desti): static
    {
        $this->Descripcio_espai_desti = $Descripcio_espai_desti;

        return $this;
    }

    public function isElegido(): ?bool
    {
        return $this->elegido;
    }

    public function setElegido(?bool $elegido): static
    {
        $this->elegido = $elegido;

        return $this;
    }

    
}
