<?php

namespace App\Entity;

use App\Repository\ArticleRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

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
     * @ORM\Column(type="string", length=500)
     */
    private $chemin_image;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $temps_realisation;

    /**
     * @ORM\OneToMany(targetEntity=Categorie::class, mappedBy="cat_art")
     */
    private $art_cat;

    public function __construct()
    {
        $this->art_cat = new ArrayCollection();
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

    public function getTempsRealisation(): ?\DateTimeInterface
    {
        return $this->temps_realisation;
    }

    public function setTempsRealisation(?\DateTimeInterface $temps_realisation): self
    {
        $this->temps_realisation = $temps_realisation;

        return $this;
    }

    /**
     * @return Collection<int, Categorie>
     */
    public function getArtCat(): Collection
    {
        return $this->art_cat;
    }

    public function addArtCat(Categorie $artCat): self
    {
        if (!$this->art_cat->contains($artCat)) {
            $this->art_cat[] = $artCat;
            $artCat->setCatArt($this);
        }

        return $this;
    }

    public function removeArtCat(Categorie $artCat): self
    {
        if ($this->art_cat->removeElement($artCat)) {
            // set the owning side to null (unless already changed)
            if ($artCat->getCatArt() === $this) {
                $artCat->setCatArt(null);
            }
        }

        return $this;
    }
}
