<?php

namespace App\Entity;

use App\Repository\AlbumPictureRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;



/**
 * @ORM\Entity(repositoryClass=AlbumPictureRepository::class)
 */
class AlbumPicture
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
    private $picture;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $title;

    /**
     * @ORM\Column(type="datetime")
     */
    private $updatedAt;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $slug;

    /**
     * @ORM\Column(type="text")
     */
    private $description;

    /**
     * @ORM\OneToMany(targetEntity=PicturesAlbum::class, mappedBy="AlbumPicture", orphanRemoval=true,cascade={"persist"})
     */
    private $picturesAlbums;

    /**
     * @ORM\ManyToOne(targetEntity=CategoryAlbum::class, inversedBy="categoryAlbum")
     */
    private $categoryAlbum;

    /**
     * @ORM\ManyToOne(targetEntity=Tag::class, inversedBy="albumPictures")
     */
    private $Tag;

    public function __construct()
    {
        $this->updatedAt = new \DateTime();
    }

    public function __toString()
    {
        return $this->picture;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getPicture(): ?string
    {
        return $this->picture;
    }

    public function setPicture(?string $picture): self
    {
        $this->picture = $picture;

        return $this;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(?string $title): self
    {
        $this->title = $title;

        return $this;
    }

    public function setUpdateAt(\DateTimeInterface $updateAt): self
    {
        $this->title = $updateAt;

        return $this;
    }

    public function getUpdateAt(): ?\DateTimeInterface
    {
        return $this->updatedAt;
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

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): self
    {
        $this->description = $description;

        return $this;
    }

    /**
     * @return Collection<int, PicturesAlbum>
     */
    public function getPicturesAlbums(): Collection
    {
        return $this->picturesAlbums;
    }

    public function addPicturesAlbum(PicturesAlbum $picturesAlbum): self
    {
        if (!$this->picturesAlbums->contains($picturesAlbum)) {
            $this->picturesAlbums[] = $picturesAlbum;
            $picturesAlbum->setAlbumPicture($this);
        }

        return $this;
    }

    public function removePicturesAlbum(PicturesAlbum $picturesAlbum): self
    {
        if ($this->picturesAlbums->removeElement($picturesAlbum)) {
            // set the owning side to null (unless already changed)
            if ($picturesAlbum->getAlbumPicture() === $this) {
                $picturesAlbum->setAlbumPicture(null);
            }
        }

        return $this;
    }

    public function getCategoryAlbum(): ?CategoryAlbum
    {
        return $this->categoryAlbum;
    }

    public function setCategoryAlbum(?CategoryAlbum $categoryAlbum): self
    {
        $this->categoryAlbum = $categoryAlbum;

        return $this;
    }

    public function getTag(): ?Tag
    {
        return $this->Tag;
    }

    public function setTag(?Tag $Tag): self
    {
        $this->Tag = $Tag;

        return $this;
    }






}
