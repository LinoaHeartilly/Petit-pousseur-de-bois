<?php

namespace App\Entity;

use App\Repository\CategorieRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=CategorieRepository::class)
 */
class Categorie
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
    private $name;

    /**
     * @ORM\OneToMany(targetEntity=Article::class, mappedBy="art_cat")
     */
    private $cat_art;

    public function __construct()
    {
        $this->cat_art = new ArrayCollection();
    }



    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @return Collection<int, Article>
     */
    public function getCatArt(): Collection
    {
        return $this->cat_art;
    }

    public function addCatArt(Article $catArt): self
    {
        if (!$this->cat_art->contains($catArt)) {
            $this->cat_art[] = $catArt;
            $catArt->setArtCat($this);
        }

        return $this;
    }

    public function removeCatArt(Article $catArt): self
    {
        if ($this->cat_art->removeElement($catArt)) {
            // set the owning side to null (unless already changed)
            if ($catArt->getArtCat() === $this) {
                $catArt->setArtCat(null);
            }
        }

        return $this;
    }

}
