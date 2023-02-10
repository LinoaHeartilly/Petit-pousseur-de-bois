<?php

namespace App\Entity;

use App\Repository\ArticleRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use phpDocumentor\Reflection\Types\Integer;

/**
 * @ORM\Entity(repositoryClass=ArticleRepository::class)
 */
class Article
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $nom;

    /**
     * @ORM\Column(type="string", length=500, nullable=true)
     */
    private $description;

    /**
     * @ORM\Column(type="integer")
     */
    private $prix;

    /**
     * @ORM\Column(type="string", length=500, nullable=true)
     */
    private $chemin_image;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $temps_realisation;

    /**
     * @ORM\ManyToOne(targetEntity=Categorie::class, inversedBy="cat_art")
     * @ORM\JoinColumn(nullable=false)
     */
    private $art_cat;

    public function __construct()
    {
        
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

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getPrix(): ?int
    {
        return $this->prix;
    }

    public function setPrix(int $prix): self
    {
        $this->prix = $prix;

        return $this;
    }

    public function getCheminImage(): ?string
    {
        return $this->chemin_image;
    }

    public function setCheminImage(string $chemin_image): self
    {
        $this->chemin_image = $chemin_image;

        return $this;
    }

    public function getTempsRealisation(): ?int
    {
        return $this->temps_realisation;
    }

    public function setTempsRealisation(?int $temps_realisation): self
    {
        $this->temps_realisation = $temps_realisation;

        return $this;
    }

    public function getArtCat(): ?Categorie
    {
        return $this->art_cat;
    }

    public function setArtCat(?Categorie $art_cat): self
    {
        $this->art_cat = $art_cat;

        return $this;
    }

}
