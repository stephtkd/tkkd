<?php

namespace App\Entity;

use App\Repository\AlbumPictureRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\File;
use Vich\UploaderBundle\Mapping\Annotation as Vich;

/**
 * @ORM\Entity(repositoryClass=AlbumPictureRepository::class)
 * @Vich\Uploadable
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
     * @ORM\OneToMany(targetEntity="AlbumPictureImages", mappedBy="AlbumPicture",cascade={"persist"})
     */
    private $albumPictureImages;


    public function __construct()
    {
        $this->updatedAt = new \DateTime();
        $this->albumPictureImages = new ArrayCollection();
    }


    public function getId(): ?int
    {
        return $this->id;
    }


    public function setPicture($picture)
    {
        $this->picture = $picture;

        return $this;
    }

    public function getPicture()
    {
        return $this->picture;
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

    public function getUpdatedAt(): ?\DateTimeInterface
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(\DateTimeInterface $updatedAt): self
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    /**
     * @return Collection<int, AlbumPictureImages>
     */
    public function getAlbumPictureImages(): Collection
    {
        return $this->albumPictureImages;
    }

    public function addAlbumPictureImage(AlbumPictureImages $albumPictureImage): self
    {
        if (!$this->albumPictureImages->contains($albumPictureImage)) {
            $this->albumPictureImages[] = $albumPictureImage;
            $albumPictureImage->setAlbumPicture($this);
        }

        return $this;
    }

    public function removeAlbumPictureImage(AlbumPictureImages $albumPictureImage): self
    {
        if ($this->albumPictureImages->removeElement($albumPictureImage)) {
            // set the owning side to null (unless already changed)
            if ($albumPictureImage->getAlbumPicture() === $this) {
                $albumPictureImage->setAlbumPicture(null);
            }
        }

        return $this;
    }

}
