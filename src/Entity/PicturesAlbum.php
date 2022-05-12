<?php

namespace App\Entity;

use App\Repository\PicturesAlbumRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=PicturesAlbumRepository::class)
 */
class PicturesAlbum
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
    private $images;

    /**
     * @ORM\ManyToOne(targetEntity=AlbumPicture::class, inversedBy="picturesAlbums")
     * * @ORM\JoinColumn(nullable=false)
     */
    private $AlbumPicture;

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

    public function getImages(): ?string
    {
        return $this->images;
    }

    public function setImages(string $images): self
    {
        $this->images = $images;

        return $this;
    }

    public function getAlbumPicture(): ?AlbumPicture
    {
        return $this->AlbumPicture;
    }

    public function setAlbumPicture(?AlbumPicture $AlbumPictures): self
    {
        $this->AlbumPicture = $AlbumPictures;

        return $this;
    }
}
