<?php

namespace App\Entity;

use App\Repository\AlbumPictureImagesRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\File;
use Vich\UploaderBundle\Mapping\Annotation as Vich;

/**
 * @ORM\Entity(repositoryClass=AlbumPictureImagesRepository::class)
 */
class AlbumPictureImages
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
    private $image;

    /**
     * @Vich\UploadableField(mapping="AlbumPictureImages", fileNameProperty="image")
     * @var File
     */
    private $imageFile;

    /**
     * @ORM\Column(type="datetime")
     */
    private $updatedAt;

    /**
     * @ORM\OneToMany(targetEntity="AlbumPicture", mappedBy="AlbumPictureImages")
     */
    private $albumPicture;

    public function __construct()
    {
        $this->updatedAt = new \DateTime();
        $this->albumPicture = new ArrayCollection();
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

    public function setImageFile(File $imageFile): void
    {
        $this->imageFile = $imageFile;

        if ($imageFile) {
            // It is required that at least one field changes if you are using doctrine
            // otherwise the event listeners won't be called and the file is lost
            $this->updatedAt = new \DateTime();
        }
    }

    public function getImageFile(): ?File
    {
        return $this->imageFile;
    }

    public function setImage($image)
    {
        $this->image = $image;

        return $this;
    }

    public function getImage()
    {
        return $this->image;
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
     * @return Collection<int, AlbumPicture>
     */
    public function getAlbumPicture(): Collection
    {
        return $this->albumPicture;
    }

    public function addAlbumPicture(AlbumPicture $albumPicture): self
    {
        if (!$this->albumPicture->contains($albumPicture)) {
            $this->albumPicture[] = $albumPicture;
            $albumPicture->setAlbumPictureImages($this);
        }

        return $this;
    }

    public function removeAlbumPicture(AlbumPicture $albumPicture): self
    {
        if ($this->albumPicture->removeElement($albumPicture)) {
            // set the owning side to null (unless already changed)
            if ($albumPicture->getAlbumPictureImages() === $this) {
                $albumPicture->setAlbumPictureImages(null);
            }
        }

        return $this;
    }
}
