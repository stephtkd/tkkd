<?php

namespace App\Entity;

use App\Repository\CategoryRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=CategoryRepository::class)
 */
class CategoryAlbum
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
     * @ORM\Column(type="string", length=255)
     */
    private $slug;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $color;

    /**
     * @ORM\OneToMany(targetEntity=AlbumPicture::class, mappedBy="categoryAlbum")
     */
    private $categoryAlbum;

    public function __construct()
    {
        $this->categoryAlbum = new ArrayCollection();
    }


    public function __toString()
    {
        return $this->name;
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

    public function getSlug(): ?string
    {
        return $this->slug;
    }

    public function setSlug(string $slug): self
    {
        $this->slug = $slug;

        return $this;
    }

    public function getColor(): ?string
    {
        return $this->color;
    }

    public function setColor(string $color): self
    {
        $this->color = $color;

        return $this;
    }

    /**
     * @return Collection<int, AlbumPicture>
     */
    public function getCategoryAlbum(): Collection
    {
        return $this->categoryAlbum;
    }

    public function addCategoryAlbum(AlbumPicture $categoryAlbum): self
    {
        if (!$this->categoryAlbum->contains($categoryAlbum)) {
            $this->categoryAlbum[] = $categoryAlbum;
            $categoryAlbum->setCategoryAlbum($this);
        }

        return $this;
    }

    public function removeCategoryAlbum(AlbumPicture $categoryAlbum): self
    {
        if ($this->categoryAlbum->removeElement($categoryAlbum)) {
            // set the owning side to null (unless already changed)
            if ($categoryAlbum->getCategoryAlbum() === $this) {
                $categoryAlbum->setCategoryAlbum(null);
            }
        }

        return $this;
    }


}
